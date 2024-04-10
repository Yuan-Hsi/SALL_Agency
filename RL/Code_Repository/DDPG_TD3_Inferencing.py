from DDPG_TD3_Infrustructure import TD3
import numpy as np
from sqlalchemy import create_engine
import pandas as pd
import Data_baseket
import math
import matplotlib.pyplot as plt
import base64
import io
from io import BytesIO

def avg_fill(data,col_list):
    for col in col_list:
        na_array = data[col][data[col].isna()].index
        for na in na_array:
            forward = data[col][na-1:na-4].mean()
            backward = data[col][na+1:na+4].mean()
            if(math.isnan(forward) and math.isnan(backward)):
                fill = 0
            elif(math.isnan(forward) or math.isnan(backward)):
                if(math.isnan(forward)):
                    fill = backward
                else:
                    fill = forward
            else:
                fill = (forward + backward)/2 

            data[col][na] = fill
    return data

def calculate_ema(prices, days, smoothing=2):
    ema = [sum(prices[:days]) / days]
    for price in prices[days:]:
        ema.append((price * (smoothing / (1 + days))) + ema[-1] * (1 - (smoothing / (1 + days))))
    return ema

def MACD(list_input,prices):
    ema_short = calculate_ema(prices, 12)
    ema_long = calculate_ema(prices, 26)
    len(ema_short)
    dif = []
    for i in range(0,len(prices)-len(ema_short)):
        tmp = ema_short[i]
        ema_short.insert( i, tmp)

    for j in range(0,len(prices)-len(ema_long)):
        tmp = ema_long[i]
        ema_long.insert( i, tmp)

    for k in range(0,len(prices)):
        dif.append(ema_short[k] - ema_long[k])

    MACD = calculate_ema(dif, 9)
    for j in range(0,len(prices)-len(MACD)):
        tmp = MACD[i]
        MACD.insert( i, tmp)

    MACD_arr = np.array(MACD).reshape(len(MACD),1)
    list_input = np.hstack((list_input,MACD_arr))
    return list_input

def tradingGraph(account,agent,host):

    # -------------- DATA EXCTRATION -----------------

    # 取這個帳戶的列
    DATABASE = {
    'host': host,
    'port': '8989',
    'database': 'AP',
    'user': 'root',
    'password': 'A!Lab502'
    }

    # 连接数据库
    engine = create_engine("mysql+pymysql://{user}:{pw}@{host}:{port}/{db}"\
                        .format(host=DATABASE['host'],port=DATABASE['port'], db=DATABASE['database'], user=DATABASE['user'], pw=DATABASE['password'])\
                        , echo=False)

    query = "SELECT * FROM `Agent_data` WHERE `Account` = '" + account +"' AND Agent = '" +agent+"'" 
    result = engine.execute(query)
    info = pd.DataFrame(result.fetchall(),columns=list(result.keys()))

    #取會用到的參數名稱
    select = ''
    para_list = list(result.keys())[list(result.keys()).index("開盤價(元)"):list(result.keys()).index("股利殖利率-TSE")]
    for para in para_list:
        if(info[para][0] == 1):
            select = select+',`'+para+'`'
    select = select[1:]

    DATABASE = {
    'host': host,
    'port': '8989',
    'database': 'stock_data',
    'user': 'root',
    'password': 'A!Lab502'
    }

    # 连接数据库
    engine = create_engine("mysql+pymysql://{user}:{pw}@{host}:{port}/{db}"\
                        .format(host=DATABASE['host'],port=DATABASE['port'], db=DATABASE['database'], user=DATABASE['user'], pw=DATABASE['password'])\
                        , echo=False)

    #连接数据表
    query = "SELECT "+ select + " FROM `"+ info['stock_num'][0] +"` ORDER BY `年月日` DESC Limit 50"
    result = engine.execute(query)
    data_df = pd.DataFrame(result.fetchall(),columns=list(result.keys()))
    data_df = data_df.loc[::-1].reset_index(drop=True)


    # -------------- PREPROCESSING -----------------

    if(info['AVG_fill'][0] == 1):
        data_df = avg_fill(data_df,list(result.keys()))
    else:
        data_df.fillna(info['Custom_fill'][0], inplace = True)

    price_key = info.iloc[0]['買賣價']
    data = data_df.to_numpy()
    price = data_df[price_key].values
    og_data = MACD(data,price)
    data = MACD(data,price)
    print(price)

    data_and_space =  Data_baseket.get_data(account,agent,price_key)
    filters = data_and_space.filters
    training_data = data_and_space.og_data

    if 'std_scaler' in filters:
        data = filters['std_scaler'].transform(data)
    if 'norm_scaler' in filters:
        data = filters['norm_scaler'].transform(data)
    if 'scaler' in filters:
        data = filters['scaler'].transform(data)

    data[data == 0] = 1e-100
    shape = data.shape
    new_shape = (shape[0], shape[1] + 2)
    new_x = np.empty(new_shape, dtype='float64')
    new_x[:, :-2] = data

    # ----------- LOAD MODEL -----------------

    state_dim = (10,new_x.shape[1])
    action_dim = 1
    max_action = 1
    file_name = "TD3_" + account + "_" + agent
    action_arr = []
    policy = TD3(state_dim, action_dim, max_action,42,1e-6,1e-6)
    policy.load(file_name, '../Model_Repository/pytorch_models')

    # ----------- LOAD RECENT PRICE AND TRADING PARAMETERS -----------------

    # price setting (to calculate the buy_max, sell_max)
    price_index = 0
    space_dict = data_df.columns.values.tolist()
    for key in space_dict:
        if(price_key == key):break
        price_index+=1
    price = og_data[:,price_index]
    capital = info.iloc[0]['invest_budget']
    left_money = capital
    scaler = capital/np.min(training_data[:,price_index])

    def fitting_room(value,scaler):
        # 检查字典是否为空
        if not filters:
            return value
        else:
            return value/scaler
        
    buy_maximum = math.floor(capital / price[9])
    buy_list = [buy_maximum]
    new_x[0:10,-2]=fitting_room(buy_maximum,scaler)
    sell_maximum = 0
    sell_list = [sell_maximum]
    new_x[0:10,-1]=sell_maximum

    # ----------- TRADING -----------------

    for i in range(10,new_x.shape[0]+1):
        action = policy.evaluate_action(new_x[i-10:i])
        action_arr.append(action)
        
        if(i == new_x.shape[0]):
            break
            
        if action > 0.01:
            amount = math.floor(buy_maximum * action)
            if amount >=1:
                    left_money -= price[i-1] * amount * (1 + 0.001425)
                    sell_maximum += amount
                    sell_list.append(sell_maximum)
                    new_x[i][-1] = fitting_room(sell_maximum,scaler)
            else:
                new_x[i][-1] = new_x[i-1][-1]
                sell_list.append(sell_maximum)

        
        if action < -0.01:
            amount = math.floor(abs(sell_maximum * action))
            if amount >=1:
                left_money += price[i-1]  * amount * (1 - 0.001425 - 0.003) 
                sell_maximum -= amount
                sell_list.append(sell_maximum)
                new_x[i][-1] = fitting_room(sell_maximum,scaler)
            else:
                new_x[i][-1] = new_x[i-1][-1]
                sell_list.append(sell_maximum)
        
        if action > -0.01 and action < 0.01:
            new_x[i][-1] = new_x[i-1][-1]
            sell_list.append(sell_maximum)
        
        buy_maximum = math.floor(left_money / price[i])
        buy_list.append(buy_maximum)
        new_x[i][-2] = fitting_room(buy_maximum,scaler)

    # ----------- GRAPHING -----------------
    pic_IObytes = io.BytesIO()
    price_arr = price[-41:]
    n_list = [x for x in range(9,len(price))]

    # 製作figure
    fig = plt.figure()

    #圖表的設定
    ax = fig.add_subplot(1, 1, 1)

    #直線圖
    ax.plot(n_list,price_arr, color='grey',linewidth=0.5,label='price trend')
    #散佈圖
    scatter = ax.scatter(n_list, price_arr, c=action_arr,vmin=-1, vmax=1, cmap='coolwarm',s=15)

    plt.title('Recent 50 Days Trading')
    plt.ylabel('Price')
    plt.xlabel('Day')
    # 添加顏色條
    plt.colorbar(scatter, label='Intensity of Selling/Buying', shrink=0.7, orientation='horizontal')
    plt.subplots_adjust(top=0.95, bottom=0.005)
    plt.legend()
    plt.savefig(pic_IObytes,  format='png')
    plt.close(fig)
    pic_IObytes.seek(0)
    pic_hash = base64.b64encode(pic_IObytes.read())
    new_encoded = str(pic_hash)
    new_encoded = new_encoded[2:-1]
    uri = 'data:image/png;base64,{}'.format(new_encoded)

    roi = (left_money+price[-1]*sell_maximum-capital)/capital
    buy_list = np.round(buy_list, decimals=3).tolist()
    sell_list = np.round(sell_list, decimals=3).tolist()

    return uri,roi,buy_list,sell_list,np.round(og_data.T[-1][9:], decimals=3).tolist()

if __name__ == '__main__':
    graph,roi = tradingGraph('Guest','filter','localhost')
