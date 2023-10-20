import pandas as pd
import requests
import time
from fake_useragent import UserAgent
import json
from bs4 import BeautifulSoup
import datetime
import re
import threading
import time
from sqlalchemy import create_engine, MetaData
from sqlalchemy import Table, Column, Date, Integer, String, ForeignKey,update
import schedule
user_agent = UserAgent()

def stock_num(code):
    
    try:
        data_link = f'https://www.moneydj.com/etf/x/basic/basic0003.xdjhtm?etfid='+ code +'.tw'
        res = requests.get(url=data_link, headers={"User-Agent": user_agent.random})
        soup = BeautifulSoup(res.text,'html.parser') 
        num_text = soup.find_all("td" ,class_="col02")[1].string
        num = re.findall(r'\d+', num_text)
        eq = float(num[0] + '.' + num[1])
        data_link = f'https://tw.stock.yahoo.com/quote/'+code+'.TW/profile'
        res = requests.get(url=data_link, headers={"User-Agent": user_agent.random})
        asset = float(res.text.split('資產規模')[1].split('</div>')[0].rsplit('>')[-1].replace(',',''))
        
        return int(asset/eq*1e6)
    except:
        if(code == '0055' or code == '0056' or code == '0050' or code == '0053' or code == '0052'):
            time.sleep(20)
            return stock_num(code)
        print("此股票無資料： ",code)
        try:
            unsuccess = pd.read_csv('../File_repository/Unsuccess.csv')
            new=pd.DataFrame({'Code':code},index=[0])
            unsuccess=unsuccess.append(new,ignore_index=True)
            unsuccess.to_csv("../File_repository/Unsuccess.csv", encoding="utf_8_sig", index= False)
        except:
            pass
        return False
    
def daily_collect(start,end):
    
    # 資料庫連線
    DATABASE = {
      'host': 'localhost',
      'port': '8989',
      'database': 'stock_data',
      'user': 'root',
      'password': 'root'
    }
    engine = create_engine("mysql+pymysql://{user}:{pw}@{host}:{port}/{db}"\
                           .format(host=DATABASE['host'],port=DATABASE['port'], db=DATABASE['database'], user=DATABASE['user'], pw=DATABASE['password'])\
                          , echo=False)
    # 绑定引擎
    metadata = MetaData(engine)
    # 连接引擎
    conn = engine.connect()
    
    
    # 拿股票清單
    code_data = pd.read_csv('../File_Repository/Unsuccess.csv', dtype={'Code':str})
    code_arr = code_data['Code'].to_list()

    # 股價資料
    price_link = 'https://openapi.twse.com.tw/v1/exchangeReport/STOCK_DAY_ALL'
    price_res = requests.get(url=price_link)
    price_data = price_res.json()
    price_code = []
    for i in range(len(price_data)):
        price_code.append(price_data[i]["Code"])
            
    # 融資資料
    loan_link = f'https://openapi.twse.com.tw/v1/exchangeReport/MI_MARGN'
    loan_res = requests.get(url=loan_link)
    loan_data = loan_res.json()
    loan_code = []
    for i in range(len(loan_data)):
        loan_code.append(loan_data[i]["股票代號"])
    
    # 本益比、殖利率及股價淨值比 資料
    gain_link = f'https://openapi.twse.com.tw/v1/exchangeReport/BWIBBU_ALL'
    gain_res = requests.get(url=gain_link)
    gain_data = gain_res.json()
    gain_code = []
    for i in range(len(gain_data)):
        gain_code.append(gain_data[i]["Code"])
        
    # 週轉率
    num_link = f'https://openapi.twse.com.tw/v1/opendata/t187ap03_L'
    res_num = requests.get(url=num_link)
    data_num = res_num.json()
    num_code = []
    for i in range(len(data_num)):
        num_code.append(data_num[i]["公司代號"])
    
    # 前一個開盤日
    symbol_link = 'https://openapi.twse.com.tw/v1/exchangeReport/FMTQIK'
    resp = requests.get(url=symbol_link, headers={
        "User-Agent": user_agent.random
    })
    data = resp.json()
    year = int(data[-1]['Date'][:3])+1911
    month = int(data[-1]['Date'][3:5])
    day = int(data[-1]['Date'][5:])
    yesterday = str(datetime.datetime(year, month, day))[:10]


    stock_baseket = {}
    for i in range(start,end):
        
        cur_code = code_arr[i]
        try:
            price_index = price_code.index(cur_code)
        except:
            continue
        
        # 连接数据表
        txt = "SELECT `年月日` FROM `"+ cur_code +"`  ORDER BY `年月日` DESC LIMIT 0 , 1" 
        last_date = engine.execute(txt)
        txt = "DELETE FROM `"+ cur_code +"` WHERE `年月日` = '" + last_date.fetchall()[0][0] +"'"
        result = engine.execute(txt)
        code_table = Table(cur_code, metadata, autoload=True)

        #print('\r 目前正在蒐集 '+ price_data[i]['Code'] +' 的資料，已完成了: ' +str(i+1) +'/'+ str(len(price_code))+' 個',end='',flush=True)
        # 三大法人
        data_link = f'https://tw.stock.yahoo.com/quote/'+ cur_code +'.TW/institutional-trading'
        res = requests.get(url=data_link)
        soup = BeautifulSoup(res.text,'html.parser')

        done = False
        try:
            info = soup.find_all("div",string=re.compile(str(yesterday).replace('-','/')))[0].find_parents("li")
            done = True
        except:
            try:
                unsuccess = pd.read_csv('../File_Repository/Unsuccess.csv')
                new=pd.DataFrame({'Code':cur_code},index=[0])
                unsuccess=unsuccess.append(new,ignore_index=True)
                unsuccess.to_csv("../File_repository/Unsuccess.csv", encoding="utf_8_sig", index= False)
            except:
                pass
            print("無法人投資資料： ",cur_code)
        
        big3 = ["外資","投信","自營商","合計","外資籌碼","漲跌幅","成交量"]
        big3_baseket={}
        if(done):
            for k in range(0,7):
                stock_info = info[0].find_all("span",class_="Jc(fe)")
                if(len(stock_info) == 6 and k == 4):
                    big3_baseket[big3[5]] = info[0].find_all("span",class_="Jc(fe)")[4].text
                    big3_baseket[big3[6]] = info[0].find_all("span",class_="Jc(fe)")[5].text
                    break
                big3_baseket[big3[k]] = info[0].find_all("span",class_="Jc(fe)")[k].text
        else:
            for k in range(0,7):
                big3_baseket[big3[k]] = ''
        
        try:
            loan_index = loan_code.index(cur_code)
            l_b = float(loan_data[loan_index]['融資買進']) if loan_data[loan_index]['融資買進']!='' else 0
            l_s = float(loan_data[loan_index]['融資賣出']) if loan_data[loan_index]['融資賣出']!='' else 0
            l_l = float(loan_data[loan_index]['融資今日餘額']) if loan_data[loan_index]['融資今日餘額']!='' else 0
            lt_b = float(loan_data[loan_index]['融券買進']) if loan_data[loan_index]['融券買進']!='' else 0
            lt_s = float(loan_data[loan_index]['融券賣出']) if loan_data[loan_index]['融券賣出']!='' else 0
            lt_l = float(loan_data[loan_index]['融券今日餘額']) if loan_data[loan_index]['融券今日餘額']!='' else 0
        except:
            l_b = None
            l_s = None
            l_l = None
            lt_b = None
            lt_s = None
            lt_l = None
            
        try:
            gain_index = gain_code.index(cur_code)
            PE = float(gain_data[gain_index]['PEratio'])
            DY = float(gain_data[gain_index]['DividendYield'])
            PB = float(gain_data[gain_index]['PBratio'])
        except:
            PE = None
            DY = None
            PB = None
            
        try:
            num_index = num_code.index(cur_code)
            turnover = round(float(price_data[price_index]['TradeVolume'].replace(',',''))/float(data_num[num_index]['實收資本額'])/10,4)
        except:
            num = stock_num(cur_code)
            if(num != False):
                try:
                    turnover = round(float(price_data[price_index]['TradeVolume'])/num * 100,4)
                except:
                    try:
                        unsuccess = pd.read_csv('../File_repository/Unsuccess.csv')
                        new=pd.DataFrame({'Code':cur_code},index=[0])
                        unsuccess=unsuccess.append(new,ignore_index=True)
                        unsuccess.to_csv("../File_repository/Unsuccess.csv", encoding="utf_8_sig", index= False)
                    except:
                        pass
                    print(cur_code," 無成交量")
                    turnover = 0
            else:
                turnover = 0

        txt = "SELECT `收盤價(元)` FROM `"+ cur_code +"`  ORDER BY `年月日` DESC LIMIT 0 , 1" 
        result = engine.execute(txt)
        try:
            close_price_before_y = result.fetchall()[0][0]
        except:
            close_price_before_y == None

        stock_baseket = {
        "年月日" : str(yesterday).replace('-','/')
        ,"開盤價(元)": float(price_data[price_index]['OpeningPrice']) if price_data[price_index]['OpeningPrice']!='' else close_price_before_y
        ,"最高價(元)": float(price_data[price_index]['HighestPrice']) if price_data[price_index]['HighestPrice']!='' else close_price_before_y
        ,"最低價(元)":float(price_data[price_index]['LowestPrice']) if price_data[price_index]['LowestPrice']!='' else close_price_before_y
        ,"收盤價(元)": float(price_data[price_index]['ClosingPrice']) if price_data[price_index]['ClosingPrice']!='' else close_price_before_y
        ,"成交量(千股)": float(price_data[price_index]['TradeVolume'].replace(',',''))/1000 if price_data[price_index]['TradeVolume']!='' else 0
        ,"成交值(千元)": float(price_data[price_index]['TradeValue'].replace(',',''))/1000 if price_data[price_index]['TradeValue']!='' else 0
        ,"報酬率％": round((float(price_data[price_index]['ClosingPrice'])-close_price_before_y)/close_price_before_y*100,4) if (price_data[price_index]['ClosingPrice']!='' and  close_price_before_y != None) else 0
        ,"週轉率％":turnover
        ,"成交筆數(筆)": float(price_data[price_index]['Transaction']) if price_data[price_index]['Transaction']!='' else None
        ,"外資買賣超(千股)": float(big3_baseket['外資'].replace(',','')) if big3_baseket['外資']!='' else None
        ,"投信買賣超(千股)": float(big3_baseket['投信'].replace(',','')) if big3_baseket['投信']!='' else None
        ,"自營買賣超(千股)":float(big3_baseket['自營商'].replace(',','')) if big3_baseket['自營商']!='' else None
        ,"合計買賣超(千股)":float(big3_baseket['合計'].replace(',','')) if big3_baseket['合計']!='' else None
        ,"融資餘額(張)":l_l
        ,"融資買進(張)": l_b
        ,"融資賣出(張)": l_s
        ,"融券買進(張)": lt_b
        ,"融券餘額(張)": lt_l
        ,"融券賣出(張)": lt_s
        ,"本益比-TSE": PE if PE!='' else None
        ,"股價淨值比-TSE": PB if PB!='' else None
        ,"股利殖利率-TSE": DY if DY!='' else None
        }

        # 輸入資料庫
        conn.execute(code_table.insert(), [stock_baseket])
    conn.close()
        
def job():
    code_data = pd.read_csv('../File_Repository/Unsuccess.csv', dtype={'Code':str})
    code_arr = code_data['Code'].to_list()

    # 建立 5 個子執行緒
    threads = []
    threads.append(threading.Thread(target = daily_collect, args = (0,int(len(code_arr)))))

    for i in range(1): 
        threads[i].start()

    # 主執行緒繼續執行自己的工作
    # ...

    # 等待所有子執行緒結束
    for i in range(1):
        threads[i].join()
    code_data.drop(code_data.index, inplace=True)
    code_data.to_csv("../File_repository/Unsuccess.csv", encoding="utf_8_sig", index= False)
    print("Done.")

if __name__ == '__main__':
    schedule.every().day.at("08:00").do(job)
    while True:schedule.run_pending()