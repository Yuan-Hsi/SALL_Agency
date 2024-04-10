import pandas as pd
from fastapi import FastAPI, WebSocket
from fastapi import Body
from fastapi.responses import JSONResponse
import uvicorn
from pydantic import BaseModel
from fastapi.middleware.cors import CORSMiddleware
from sqlalchemy import create_engine, MetaData
from sqlalchemy import update,Table, Column, Date, Integer, String, ForeignKey, and_
import env_test
import matplotlib.pyplot as plt
import numpy as np
import io
import base64
import os
import DDPG_TD3_Training
import time
import asyncio
import cmd
from hyperopt import hp,tpe,fmin,STATUS_OK
import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt
import DDPG_TD3_Inferencing
import math
import inference_system
import shutil

# 存储所有连接的 WebSocket 客户端
websockets = set()

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

class Output_log(BaseModel):
    account: str
    agent_name: str

class parameters(BaseModel):
    account: str
    agent_name: str
    max_timesteps_low: int
    start_timesteps_low: int
    discount_low: int
    expl_noise_low: int
    policy_noise_low: int
    tau_low: int
    max_timesteps_up: int
    start_timesteps_up: int
    discount_up: int
    expl_noise_up: int
    policy_noise_up: int
    tau_up: int
    update_round: int
    evaluate_step: int
    test_times: int
    actor_lr: int
    target_lr: int
    batch_size: int

class Input_code(BaseModel):
    code : str
    account: str
    agent_name: str

class inference_para(BaseModel):
    account: str
    agent_name: str
    features: str
    data: str
    price_key: str

@app.post("/env_setting")
def coding_test(input_code: Input_code = Body(...)):


    code_dict = input_code.dict()

    code_df =  pd.DataFrame(code_dict,index=[0])
    
    code = {'py_code' : code_df.iloc[0,0],
            'account' : code_df.iloc[0,1],
            'agent_name' : code_df.iloc[0,2],
            }
    
    # 組合檔案路徑和檔案名稱
    py_name = code['account']+'_'+code['agent_name']+".py"
    filename = os.path.join("../../docker-nginx-php-mysql/web/public/custom_env", py_name)

    with open(filename, "w") as f:
        f.write(code['py_code'])
        f.close()

    #os.system('cp /Y .\\custome_env\\'+py_name+ ' ..\\..\\docker-nginx-php-mysql\\web\\public\\custome_env\\'+py_name ) # /Y 表示複寫
    #result = env_test.code_validatioin(code['account'],code['agent_name'],)
    
    return JSONResponse(code)

@app.post("/training")
def training(parameters: parameters = Body(...)):

    # 參數取得
    parameter = parameters.dict()
    parameter_df =  pd.DataFrame(parameter,index=[0])
    parameter_dic = {
            'max_timesteps_low' : parameter_df.iloc[0,2],
            'start_timesteps_low' : parameter_df.iloc[0,3],
            'discount_low' : parameter_df.iloc[0,4],
            'expl_noise_low' : parameter_df.iloc[0,5],
            'policy_noise_low' : parameter_df.iloc[0,6],
            'tau_low' : parameter_df.iloc[0,7],
            'max_timesteps_up' : parameter_df.iloc[0,8],
            'start_timesteps_up' : parameter_df.iloc[0,9],
            'discount_up' : parameter_df.iloc[0,10],
            'expl_noise_up' : parameter_df.iloc[0,11],
            'policy_noise_up' : parameter_df.iloc[0,12],
            'tau_up' : parameter_df.iloc[0,13],
            'update_round' : parameter_df.iloc[0,14],
            'test_times' : parameter_df.iloc[0,16],
            'actor_lr': parameter_df.iloc[0,17],
            'target_lr': parameter_df.iloc[0,18],
            'batch_size': parameter_df.iloc[0,19],
            }
    account = parameter_df.iloc[0,0]
    agent_name = parameter_df.iloc[0,1]
    evaluate_step = parameter_df.iloc[0,15]

    print(parameter_df.iloc[0,17])
    # 添加至目前訓練資料夾，防止他人部署
    bin_file = './current_training/'+account +'_' + agent_name+'.bin'
    with open(bin_file, 'wb') as fp:
        pass


    # 找出買賣價
    DATABASE = {
    'host': 'localhost',
    'port': '8989',
    'database': 'AP',
    'user': 'root',
    'password': 'A!Lab502'
    }
    engine = create_engine("mysql+pymysql://{user}:{pw}@{host}:{port}/{db}"\
                        .format(host=DATABASE['host'],port=DATABASE['port'], db=DATABASE['database'], user=DATABASE['user'], pw=DATABASE['password'])\
                        , echo=False)
    query = "SELECT `買賣價`,`seed`,`reward_driver`,`punish_driver`,`length`,`stock_amount`,`interest_rate`,`fee_rate`,`training_set`,`invest_budget` FROM `Agent_data` WHERE `Account` = '"+ account +"' AND`Agent` = '" + agent_name + "'" 
    result = engine.execute(query)
    baseket = result.fetchall()
    price = str(baseket[0][0])
    seed = str(baseket[0][1])
    reward_driver = str(baseket[0][2])
    punish_driver = str(baseket[0][3])
    length = str(baseket[0][4])
    stock_amount = str(baseket[0][5])
    interest_rate = str(baseket[0][6])
    fee_rate = str(baseket[0][7])
    training_set = str(baseket[0][8])
    capital = str(baseket[0][9])
    

    # 放入資料庫
    metadata = MetaData(engine)
    table = Table("Agent_data", metadata, autoload=True)
    u = update(table)
    u = u.values(parameter_dic)
    u = u.where(and_(table.c.Account == account, table.c.Agent == agent_name))
    engine.execute(u)

    # 清空 log 資料
    py_name = account + "_" + agent_name +".txt"
    filename = os.path.join("../File_Repository/training_log", py_name)

    with open(filename,"w") as f:
        f.write("")
        f.close()

    def objective(argsDict):
        if not(os.path.isfile(bin_file)):
            return 1e10
        performance, evaluation_uri,pic,amount_scaler = DDPG_TD3_Training.Train(account = account,agent = agent_name, price_key = price,
                                                              train_potion = float(training_set),seed = int(seed), capital = float(capital),
                                                              start_timesteps = parameter_dic['start_timesteps_low']+argsDict['start_timesteps'],
                                                              eval_freq = evaluate_step, max_timesteps = parameter_dic['max_timesteps_low']+argsDict["max_timesteps"], 
                                                              expl_noise = (parameter_dic['expl_noise_low']+argsDict["expl_noise"])/100, 
                                                              discount = (parameter_dic['discount_low']+argsDict['discount'])/100, 
                                                              tau = (parameter_dic['tau_low']+argsDict['tau'])/1000,
                                                              policy_noise = (parameter_dic['policy_noise_low']+argsDict['policy_noise'])/100, 
                                                              noise_clip = parameter_dic['policy_noise_up'], policy_freq = parameter_dic['update_round'],
                                                              reward_driver= float(reward_driver), punish_driver = float(punish_driver), length = int(length), 
                                                              stock_num = int(stock_amount), interest_rate = float(interest_rate),fee_rate=float(fee_rate),
                                                              batch_size=parameter_dic['batch_size'],actor_lr=parameter_dic['actor_lr'],target_lr=parameter_dic['target_lr'],)
        
        query = "SELECT `performance` FROM `Agent_data` WHERE `Account` = '"+ account +"' AND`Agent` = '" + agent_name + "'" 
        result = engine.execute(query)
        performance_b = float(result.fetchall()[0][0])
        if(performance_b > performance):
            u = update(table)
            u = u.values({'performance':performance,'amount_scaler':amount_scaler})
            u = u.where(and_(table.c.Account == account, table.c.Agent == agent_name))
            engine.execute(u)
            file_name_ac = "TD3_" + account + "_" + agent_name + "_actor.pth"
            file_name_cr = "TD3_" + account + "_" + agent_name + "_critic.pth"
            os.system('move /Y ..\\Model_Repository\\temp\\'+file_name_ac + ' ..\\Model_Repository\\pytorch_models\\'+file_name_ac ) # /Y 表示複寫
            os.system('move /Y ..\\Model_Repository\\temp\\'+file_name_cr + ' ..\\Model_Repository\\pytorch_models\\'+file_name_cr ) # /Y 表示複寫
            with open(os.path.join("../File_Repository/img_uri", py_name),"w") as f:
                f.write(evaluation_uri)
                f.close()
            pic.savefig(os.path.join("../File_Repository/img_uri", account + "_" + agent_name +".jpg"))
            
        return performance

    space = {
            "max_timesteps":hp.randint("max_timesteps", parameter_dic['max_timesteps_up']-parameter_dic['max_timesteps_low']),
            "start_timesteps":hp.randint("start_timesteps", parameter_dic['start_timesteps_up']-parameter_dic['start_timesteps_low']),
            "discount":hp.randint("discount", parameter_dic['discount_up']-parameter_dic['discount_low']),
            "expl_noise":hp.randint("expl_noise", parameter_dic['expl_noise_up']-parameter_dic['expl_noise_low']),
            "policy_noise":hp.randint("policy_noise", parameter_dic['policy_noise_up']-parameter_dic['policy_noise_low']),
            "tau":hp.randint("tau", parameter_dic['tau_up']-parameter_dic['tau_low']),
            }
    print( parameter_dic['tau_up'],parameter_dic['tau_low'])
    algo = tpe.suggest
    best = fmin(objective,space,algo=algo,max_evals=parameter_dic['test_times'])
    
    # 組合檔案路徑和檔案名稱
    py_name = account+'_'+agent_name+".txt"
    filename = os.path.join("../File_Repository/training_log", py_name)

    with open(filename, "a") as f:
        f.write("\ndone")
        f.close()

    query = "SELECT `performance` FROM `Agent_data` WHERE `Account` = '"+ account +"' AND`Agent` = '" + agent_name + "'" 
    result = engine.execute(query)
    performance_b = float(result.fetchall()[0][0])

    result_dic = {
        'max_timesteps' : int(best['max_timesteps']+ parameter_dic['max_timesteps_low']),
        'start_timesteps' : int(parameter_dic['start_timesteps_low']+best['start_timesteps']),
        'discount' : int(best['discount']+ parameter_dic['discount_low']),
        "expl_noise": int(parameter_dic['expl_noise_low']+best["expl_noise"]),
        "policy_noise":int(parameter_dic['policy_noise_low']+best['policy_noise']),
        "tau":int(parameter_dic['tau_low']+best['tau']),
        "performance":round(-float(performance_b), 4)
    }
    
    # 績效放入資料庫
    metadata = MetaData(engine)
    table = Table("Agent_data", metadata, autoload=True)
    u = update(table)
    u = u.values(result_dic)
    u = u.where(and_(table.c.Account == account, table.c.Agent == agent_name))
    engine.execute(u)

    query = "SELECT * FROM `Agent_data` WHERE `Account` = '" + account +"' AND Agent = '" +agent_name+"'" 
    result = engine.execute(query)
    info = pd.DataFrame(result.fetchall(),columns=list(result.keys()))
    info.to_csv('../File_Repository/agent_info/'+account+'_'+agent_name+'.csv')

    if os.path.isfile(bin_file):
        os.remove(bin_file)

    return JSONResponse(result_dic)


@app.post("/end_training")
def end_training(Output_log: Output_log = Body(...)):
    info_dict = Output_log.dict()
    info_df =  pd.DataFrame(info_dict,index=[0])
    info = {
            'account' : info_df.iloc[0,0],
            'agent_name' : info_df.iloc[0,1],
            }
    bin_file = './current_training/'+info['account'] +'_' + info['agent_name']+'.bin'
    if os.path.isfile(bin_file):
        os.remove(bin_file)

    result_dic = {'repond':'complete'}
    return JSONResponse(result_dic)

"""
# WebSocket 路由，用于接收连接
@app.websocket("/training_log")
async def training_log(websocket: WebSocket, Output_log: Output_log = Body(...)):

    websockets.add(websocket)
    
    info_dict = Output_log.dict()
    info_df =  pd.DataFrame(info_dict,index=[0])
    info = {
            'account' : info_df.iloc[0,0],
            'agent_name' : info_df.iloc[0,1],
            }
    
    # 組合檔案路徑和檔案名稱
    py_name = info['account']+'_'+info['agent_name']+".txt"
    filename = os.path.join("../File_Repository/training_log", py_name)

    try:
        while True:
            # 在这里你可以将每秒更新的数据发送到客户端
            # 这里只是一个示例，你可以替换成你的实际数据逻辑
            with open(filename, "a") as f:
                f.write("/n testing")
                f.close()
            
            # 打開General，讀取其內容
            with open(filename, "r") as source_file:
                source_code = source_file.read()
                source_file.close()
            
            await websocket.send_text("这是每秒更新的数据")
            await asyncio.sleep(1)
    except:
        websockets.remove(websocket)
"""

@app.post("/training_log")
def training_log(Output_log: Output_log = Body(...)):

    info_dict = Output_log.dict()
    info_df =  pd.DataFrame(info_dict,index=[0])
    info = {
            'account' : info_df.iloc[0,0],
            'agent_name' : info_df.iloc[0,1],
            }
    
    # 組合檔案路徑和檔案名稱
    py_name = info['account']+'_'+info['agent_name']+".txt"
    filename = os.path.join("../File_Repository/training_log", py_name)
    
    # 打開General，讀取其內容
    with open(filename, "r") as source_file:
        txt = source_file.read()
        source_file.close()

    respond ={"text":txt,
              "over":txt[-4:]}
    
    return JSONResponse(respond)


@app.post("/evaluation_img")
def training_log(Output_log: Output_log = Body(...)):

    info_dict = Output_log.dict()
    info_df =  pd.DataFrame(info_dict,index=[0])
    info = {
            'account' : info_df.iloc[0,0],
            'agent_name' : info_df.iloc[0,1],
            }
    
    file_path = '../File_Repository/img_uri/'+info['account']+'_'+info['agent_name']+'.jpg'
    
    with open(file_path, 'rb') as image_file:
        base64_encoded = str(base64.b64encode(image_file.read()))[2:-1]

    evaluation_uri = 'data:image/jpg;base64,{}'.format(base64_encoded)
    baseket={'img':evaluation_uri}
    return JSONResponse(baseket)

@app.post("/trading")
def trading(Output_log: Output_log = Body(...)):
    info_dict = Output_log.dict()
    info_df =  pd.DataFrame(info_dict,index=[0])
    info = {
            'account' : info_df.iloc[0,0],
            'agent_name' : info_df.iloc[0,1],
            }
    graph,roi,buy_maximum,sell_maximum,macd = DDPG_TD3_Inferencing.tradingGraph(info['account'],info['agent_name'],'localhost')
    output = {
        'graph':graph,
        'roi':"ROI: "+str(roi*100)[:5]+"%",
        'buy':buy_maximum,
        'sell':sell_maximum,
        'macd':macd,
    }
    print(sell_maximum)
    return JSONResponse(output)

@app.post("/inference")
def trading(inference_para: inference_para = Body(...)):
    info_dict = inference_para.dict()
    info_df =  pd.DataFrame(info_dict,index=[0])
    info = {
            'account' : info_df.iloc[0,0],
            'agent_name' : info_df.iloc[0,1],
            'featrues': info_df.iloc[0,2],
            'data': info_df.iloc[0,3],
            'price_key': info_df.iloc[0,4],
            }
    
    text_arr = info['data'].split('|')[:-1]
    featrues_arr = info['featrues'].split(',')
    for counter in range(len(text_arr)):
        text_arr[counter] = text_arr[counter].split(',')
    input_arr = np.array(text_arr, dtype=np.float64)
    para_dic = {}
    for i in range (input_arr.shape[0]):
        para_dic[featrues_arr[i]] = np.array(input_arr[i], dtype=np.float64)

    action = inference_system.inference(info['account'],info['agent_name'],para_dic)
    output = {
        'action':str(action)
    }
    return JSONResponse(output)

@app.post("/delete_agent")
def trading(Output_log: Output_log = Body(...)):
    info_dict = Output_log.dict()
    info_df =  pd.DataFrame(info_dict,index=[0])
    info = {
            'account' : info_df.iloc[0,0],
            'agent_name' : info_df.iloc[0,1],
            }
    
    # 刪除資料庫資料
    DATABASE = {
    'host': 'localhost',
    'port': '8989',
    'database': 'AP',
    'user': 'root',
    'password': 'A!Lab502'
    }
    engine = create_engine("mysql+pymysql://{user}:{pw}@{host}:{port}/{db}"\
                        .format(host=DATABASE['host'],port=DATABASE['port'], db=DATABASE['database'], user=DATABASE['user'], pw=DATABASE['password'])\
                        , echo=False)
    query = "Delete FROM `Agent_data` WHERE `Account` = '" + info['account'] +"' AND Agent = '" +info['agent_name']+"'" 
    result = engine.execute(query)

    # 刪除網頁使用的env
    name = info['account'] + '_' +info['agent_name']
    address = "../../docker-nginx-php-mysql/web/public/custom_env/"+name+".py"
    if os.path.isfile(address):
        os.remove(address)

    # 刪除在 Code Repository 的 env
    address = "./custom_env/"+name+".py"
    if os.path.isfile(address):
        os.remove(address)

    # 刪除在 File Repository 的檔案
    address = "../File_Repository/agent_info/"+name+".csv"
    if os.path.isfile(address):
        os.remove(address)
    address = "../File_Repository/img_uri/"+name+".jpg"
    if os.path.isfile(address):
        os.remove(address)
    address = "../File_Repository/img_uri/"+name+".txt"
    if os.path.isfile(address):
        os.remove(address)
    address = "../File_Repository/scalers/"+name
    if os.path.exists(address):
        shutil.rmtree(address)
    address = "../File_Repository/training_log/"+name+".txt"
    if os.path.isfile(address):
        os.remove(address)
    
    # 刪除在 Model Repository 的模型
    address = "../Model_Repository/pytorch_models/TD3_"+name+"_actor.pth"
    if os.path.isfile(address):
        os.remove(address)
    address = "../Model_Repository/pytorch_models/TD3_"+name+"_critic.pth"
    if os.path.isfile(address):
        os.remove(address)


    output = {
        'delete':"complete"
    }
    return JSONResponse(output)

@app.post("/deploy_agent")
def trading(Output_log: Output_log = Body(...)):
    info_dict = Output_log.dict()
    info_df =  pd.DataFrame(info_dict,index=[0])
    info = {
            'account' : info_df.iloc[0,0],
            'agent_name' : info_df.iloc[0,1],
            }
    
    # 刪除資料庫資料
    DATABASE = {
    'host': 'localhost',
    'port': '8989',
    'database': 'AP',
    'user': 'root',
    'password': 'A!Lab502'
    }
    engine = create_engine("mysql+pymysql://{user}:{pw}@{host}:{port}/{db}"\
                        .format(host=DATABASE['host'],port=DATABASE['port'], db=DATABASE['database'], user=DATABASE['user'], pw=DATABASE['password'])\
                        , echo=False)

    query_1 = "UPDATE `Agent_data` SET `Deploy` = '0' WHERE `Deploy` = '1'"
    query_2 = "UPDATE `Agent_data` SET `Deploy` = '1' WHERE `Account` = '" + info['account'] +"' AND Agent = '" +info['agent_name']+"'"
    result_1 = engine.execute(query_1)
    result_2 = engine.execute(query_2)

    # 檢查帳號是否曾經部署過
    address = "../../docker-nginx-php-mysql/web/public/deploy/" + info['account']
    if os.path.exists(address):
        shutil.rmtree(address)
    os.mkdir(address)


    #拷貝準備部屬的模型到網頁中
    actor_name = 'TD3_'+info['account']+'_'+info['agent_name']+'_actor.pth'
    critic_name = 'TD3_'+info['account']+'_'+info['agent_name']+'_critic.pth'
    commands = [
                'copy ..\\Model_Repository\\pytorch_models\\'+ actor_name +' ..\\..\\docker-nginx-php-mysql\\web\\public\\deploy\\'+ info['account'] +'\\'+info['agent_name']+'_actor.pth',
                'copy ..\\Model_Repository\\pytorch_models\\'+ critic_name +' ..\\..\\docker-nginx-php-mysql\\web\\public\\deploy\\'+ info['account'] +'\\'+info['agent_name']+'_critic.pth'
                ]

    # for i in commands:
    #     print(i)
    for command in commands:
        os.system(command)

    output = {
        'deploy':"complete"
    }
    return JSONResponse(output)

if __name__ == "__main__":
    uvicorn.run(app = 'training:app', host="0.0.0.0", port=6055, reload=False) #app = python檔名！

