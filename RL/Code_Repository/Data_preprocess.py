import pandas as pd
import numpy as np
import math
from sklearn import preprocessing
from sqlalchemy import create_engine
from gym import spaces
from gym.spaces import Box

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

def data_preprocess(agent,account):
    DATABASE = {
    'host': 'localhost',
    'port': '8989',
    'database': 'AP',
    'user': 'root',
    'password': 'root'
    }

    # 连接数据库
    engine = create_engine("mysql+pymysql://{user}:{pw}@{host}:{port}/{db}"\
                        .format(host=DATABASE['host'],port=DATABASE['port'], db=DATABASE['database'], user=DATABASE['user'], pw=DATABASE['password'])\
                        , echo=False)

    #连接数据表
    query = "SELECT * FROM `Agent_data` WHERE `Account` = '" + account +"' AND Agent = '" +agent+"'" 
    result = engine.execute(query)
    info = pd.DataFrame(result.fetchall(),columns=list(result.keys()))

    #取會用到的參數名稱
    select = ''
    para_list = list(result.keys())[12:]
    for para in para_list:
        if(info[para][0] == 1):
            select = select+',`'+para+'`'
    select = select[1:]

    #取數據
    DATABASE = {
    'host': 'localhost',
    'port': '8989',
    'database': 'stock_data',
    'user': 'root',
    'password': 'root'
    }

    # 连接数据库
    engine = create_engine("mysql+pymysql://{user}:{pw}@{host}:{port}/{db}"\
                        .format(host=DATABASE['host'],port=DATABASE['port'], db=DATABASE['database'], user=DATABASE['user'], pw=DATABASE['password'])\
                        , echo=False)

    #连接数据表
    query = "SELECT "+ select + " FROM `"+ info['stock_num'][0] +"` WHERE `年月日` BETWEEN '" + str(info['Start_date'][0]) +"' AND '" +str(info['End_date'][0])+"'" 
    result = engine.execute(query)
    data_df = pd.DataFrame(result.fetchall(),columns=list(result.keys()))

    if(info['AVG_fill'][0] == 1):
        data_df = avg_fill(data_df,list(result.keys()))
    else:
        data_df.fillna(info['Custom_fill'][0], inplace = True)

    data = data_df.to_numpy()
    price = data_df['收盤價(元)'].values
    data = MACD(data,price)

    if(info["Standardise"][0] == 1):
        from sklearn.preprocessing import StandardScaler
        scaler = StandardScaler()
        scaler.fit(data)
        data = scaler.transform(data)

    if(info["Normalize"][0] == 1):
        from sklearn.preprocessing import Normalizer
        transformer = Normalizer().fit(data)
        data = transformer.transform(data)

    if(info["Scaleing"][0] == 1):
        from sklearn.preprocessing import MinMaxScaler
        scaler = MinMaxScaler()
        scaler.fit(data)
        data = scaler.transform(data)
        
    data[data == 0] = 1e-100
    
    space_dict = {}
    i = 0
    for col in list(result.keys()):
        space_dict[col] = spaces.Box(low=np.array([data[:,i].min()]), high=np.array([data[:,i].max()]),dtype=np.float64)
    space_dict['MACD'] = spaces.Box(low=np.array([data[:,i].min()]), high=np.array([data[:,i].max()]),dtype=np.float64)
    return data,space_dict
