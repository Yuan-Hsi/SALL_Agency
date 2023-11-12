import pandas as pd
from fastapi import FastAPI
from fastapi import Body
from fastapi.responses import JSONResponse
import uvicorn
from pydantic import BaseModel
from fastapi.middleware.cors import CORSMiddleware
from sqlalchemy import create_engine, MetaData
from sqlalchemy import Table, Column, Date, Integer, String, ForeignKey
import matplotlib.pyplot as plt
import numpy as np
import io
import base64
import time
import os

    

app = FastAPI() # 建立一個 Fast API application

origins = ["*"]

app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class Input_data1_ds(BaseModel):
    state_1 : float
    state_2 : float
    state_3 : float
    state_4 : float
    state_5 : float
    state_6 : float
    state_7 : float
    state_8 : float

class Input_code(BaseModel):
    code : str
    account: str
    agent_name: str


@app.post("/data_preprocessing")
def data_preprocessing(input_data: Input_data1_ds = Body(...)):


    input_data = input_data.dict()

    input_df =  pd.DataFrame(input_data,index=[0])
    
    output_data = {'state_1' : input_df.iloc[0,0],
                   'state_2' : input_df.iloc[0,1],
                   }

    return JSONResponse(output_data)

@app.post("/coding_test")
def coding_test(input_code: Input_code = Body(...)):

    time.sleep(5)
    code_dict = input_code.dict()

    code_df =  pd.DataFrame(code_dict,index=[0])
    
    code = {'py_code' : code_df.iloc[0,0],
            'account' : code_df.iloc[0,1],
            'agent_name' : code_df.iloc[0,2],
            }
    
    # 組合檔案路徑和檔案名稱
    py_name = code['account']+'_'+code['agent_name']+".py"
    filename = os.path.join("./custom_env", py_name)
    general = os.path.join("./custom_env", "General.py")

    # 打開General，讀取其內容
    with open(general, "r") as source_file:
        source_code = source_file.read()

    # 檢查檔案是否存在
    #if not os.path.exists(filename):
        # 檔案不存在，則建立新檔案

    with open(filename, "w") as f:
        f.write(source_code + "\n")
        f.write("\n" + code['py_code'])
        f.close()

    #os.system('cp /Y .\\custome_env\\'+py_name+ ' ..\\..\\docker-nginx-php-mysql\\web\\public\\custome_env\\'+py_name ) # /Y 表示複寫
    #result = env_test.code_validatioin(code['account'],code['agent_name'],)
    
    return JSONResponse(code)


@app.post("/hello")
def test():
    return "Hello World"

@app.post("/get_graph")
def get_graph(query: str = None):


    DATABASE = {
    'host': 'host.docker.internal',
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
    result = engine.execute(query)
    df = pd.DataFrame(result.fetchall(),columns=list(result.keys()))
    baseket ={}
    baseket['keys'] = list(result.keys())
    baseket['total_len'] = int(len(df))
    for name in list(result.keys()):
        if name == '年月日':continue
        fig=plt.figure()
        baseket[name+'_N'] = int(df[name].isna().sum())
        column = df[name] 
        baseket[name+'_Z'] = int(column[column == 0].count())
        arr = df[name].fillna('').tolist()
        try:
            arr = list(filter(lambda x: x != '', arr))
        except:
            print('good')
        hist,bin_edges = np.histogram(arr,bins=70)
        plt.bar(bin_edges[1:],hist,color='#5B9BD5',width=(bin_edges[3]-bin_edges[2]) / 0.87 * 0.7 )
        pic_IObytes = io.BytesIO()
        #plt.savefig(loss_chart_path)
        plt.savefig(pic_IObytes,  format='png')
        plt.show()
        plt.close(fig)
        pic_IObytes.seek(0)
        pic_hash = base64.b64encode(pic_IObytes.read())
        new_encoded = str(pic_hash)
        new_encoded = new_encoded[2:-1]
        baseket[name] = 'data:image/png;base64,{}'.format(new_encoded)

    return JSONResponse(baseket)


@app.post("/get_data")
def get_data(code: str = None):
    
    DATABASE = {
    'host': 'host.docker.internal',
    'port': '8989',
    'database': 'stock_data',
    'user': 'root',
    'password': 'root'
    }

    # 连接数据库
    engine = create_engine("mysql+pymysql://{user}:{pw}@{host}:{port}/{db}"\
                        .format(host=DATABASE['host'],port=DATABASE['port'], db=DATABASE['database'], user=DATABASE['user'], pw=DATABASE['password'])\
                        , echo=False)

    # 绑定引擎
    #metadata = MetaData(engine)

    # 连接数据表
    #user_table = Table("0050", metadata, autoload=True)
    #txt = "SELECT `收盤價(元)` FROM `0050` WHERE 年月日 = '" + '2023/8/21' + "'"
    #result = engine.execute(txt)
    #k = result.fetchall()[0][0]

    data = pd.read_sql('SELECT * FROM `'+code+'` ', engine)
    data = data.fillna('')
    data_set ={}
    min_arr = data.iloc[0]['年月日'].replace('/','-').split('-')
    k = min_arr[0]
    for date in min_arr:
        if(date == min_arr[0]):continue
        k = k + '-'
        if(len(date)==1):
            k = k + '0' 
        k = k + date
    data_set['max_date'] = data.iloc[-1]['年月日'].replace('/','-')
    data_set['min_date'] = k
    data_set['head'] = data.columns.tolist()
    for co in data.columns:
        data_set[co] = data[co].tolist()
    return JSONResponse(data_set)

if __name__ == "__main__":
    uvicorn.run(app = 'test:app', host="0.0.0.0", port=6050, reload=True) #app = python檔名！

