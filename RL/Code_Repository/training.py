import pandas as pd
from fastapi import FastAPI
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
from hyperopt import hp,tpe,fmin,STATUS_OK

    

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
    training_set: int
    update_round: int
    evaluate_step: int
    test_times: int

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
            'training_set' : parameter_df.iloc[0,14],
            'update_round' : parameter_df.iloc[0,15],
            'test_times' : parameter_df.iloc[0,17],
            }
    account = parameter_df.iloc[0,0]
    agent_name = parameter_df.iloc[0,1]
    evaluate_step = parameter_df.iloc[0,16]

    # 找出買賣價
    DATABASE = {
    'host': 'localhost',
    'port': '8989',
    'database': 'AP',
    'user': 'root',
    'password': 'root'
    }
    engine = create_engine("mysql+pymysql://{user}:{pw}@{host}:{port}/{db}"\
                        .format(host=DATABASE['host'],port=DATABASE['port'], db=DATABASE['database'], user=DATABASE['user'], pw=DATABASE['password'])\
                        , echo=False)
    query = "SELECT `買賣價`,`seed`,`reward_driver`,`punish_driver`,`length`,`stock_amount`,`interest_rate`,`fee_rate` FROM `Agent_data` WHERE `Account` = '"+ account +"' AND`Agent` = '" + agent_name + "'" 
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

    # 放入資料庫
    metadata = MetaData(engine)
    table = Table("Agent_data", metadata, autoload=True)
    u = update(table)
    u = u.values(parameter_dic)
    u = u.where(and_(table.c.Account == account, table.c.Agent == agent_name))
    engine.execute(u)

    def objective(argsDict):
        performance, evaluation_uri = DDPG_TD3_Training.Train(account = account,agent = agent_name, price_key = price,
                                                              train_potion = parameter_dic["training_set"],seed = int(seed),start_timesteps = argsDict['start_timesteps'],
                                                              eval_freq = evaluate_step, max_timesteps = argsDict["max_timesteps"], expl_noise = argsDict["expl_noise"], 
                                                              discount = argsDict['discount'], tau = argsDict['tau'], policy_noise = argsDict['policy_noise'], 
                                                              noise_clip = parameter_dic['policy_noise_up'], policy_freq = parameter_dic['update_round'],
                                                              reward_driver= float(reward_driver), punish_driver = float(punish_driver), length = int(length), 
                                                              stock_num = int(stock_amount), interest_rate = float(interest_rate),fee_rate=float(fee_rate))
        return performance

    space = {
            "max_timesteps":hp.randint("max_timesteps", parameter_dic['max_timesteps_up']-parameter_dic['max_timesteps_low']),
            "start_timesteps":hp.randint("start_timesteps", parameter_dic['start_timesteps_up']-parameter_dic['start_timesteps_low']),
            "discount":hp.randint("discount", parameter_dic['discount_up']-parameter_dic['discount_low']),
            "expl_noise":hp.randint("expl_noise", parameter_dic['expl_noise_up']-parameter_dic['expl_noise_low']),
            "policy_noise":hp.randint("policy_noise", parameter_dic['policy_noise_up']-parameter_dic['policy_noise_low']),
            "tau":hp.randint("tau", parameter_dic['tau_up']-parameter_dic['tau_low']),
            }
    
    algo = tpe.suggest
    best = fmin(objective,space,algo=algo,max_evals=parameter_dic['test_times'])

    return JSONResponse(best)


if __name__ == "__main__":
    uvicorn.run(app = 'training:app', host="0.0.0.0", port=6055, reload=True) #app = python檔名！

