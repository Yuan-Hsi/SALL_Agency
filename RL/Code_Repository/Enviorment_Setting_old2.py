from gym import Env, spaces
from gym.spaces import Discrete, Box, Dict
import pandas as pd
import numpy as np
import random
import math
from sklearn import preprocessing

def calculate_ema(prices, days, smoothing=2):
    ema = [sum(prices[:days]) / days]
    for price in prices[days:]:
        ema.append((price * (smoothing / (1 + days))) + ema[-1] * (1 - (smoothing / (1 + days))))
    return ema

def MACD(list_input):
    prices = list_input[:,0]
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

class ETFenv(Env):
    
    def __init__(self, id = "0050",reward_driver=1, punish_driver = 3, length = 100, stock_num = 1000, interest_rate = 0.05):

        address = "../File_Repository/Train set/" + id + "_train.csv"
        dataset = pd.read_csv(address)
        X = dataset.iloc[:, 1:].values
        X = MACD(list_input = X)
        min_max_scaler = preprocessing.MinMaxScaler(feature_range=(0.00000001, 1))
        self.X = min_max_scaler.fit_transform(X)


        global rd
        rd = random.randint(0,len(X)-(length+3))

        self._max_episode_steps = length
        self.state = X[rd]
        self.price = self.X[rd+1][0]
        self.next_price = self.X[rd+2][0]
        self.interest_rate = interest_rate
        #set length
        self.length = self._max_episode_steps
        #set holding
        self.reward_driver = reward_driver
        self.punish_driver = punish_driver
        self.stock = stock_num
        self.hold_times=0
        self.reward = 0
        self.asset = []
        self.lastday = 0
        self.hold = False
        self.buy = False
        self.sell = False
        
        #Action Buy(0),Sell(1),Hold(2)
        self.action_space = Box(low = -1, high= 1, shape=(1,), dtype=np.float)
        #Price array
        self.observation_space = spaces.Dict(dict(Price =spaces.Box(low=np.array([0]), high=np.array([150])),
                                                  Volume=spaces.Box(low=np.array([0]), high=np.array([150000])),
                                                  High = spaces.Box(low=np.array([0]), high=np.array([150])),
                                                  Low = spaces.Box(low=np.array([0]), high=np.array([150])),
                                                  Close = spaces.Box(low=np.array([0]), high=np.array([150])),
                                                  LB = spaces.Box(low=np.array([0]), high=np.array([40000])),
                                                  LS = spaces.Box(low=np.array([0]), high=np.array([90000])),
                                                  Turnover = spaces.Box(low=np.array([0]), high=np.array([12])),
                                                  MACD = spaces.Box(low=np.array([-2]), high=np.array([2]))))
        
    def step(self, action):
        global rd
        self.length -=1
        

        amount = abs(math.floor(self.stock  * action))

        if action > 0:
            reward_do = math.log10(float(self.next_price/self.price)) * amount
            reward_leave = math.log10(1+float(self.interest_rate*30/365)) * (self.stock - amount) * self.price
            reward_function_sum = reward_do + reward_leave
            self.reward  = reward_function_sum * self.reward_driver if reward_function_sum >0  else reward_function_sum * self.punish_driver
            
        
        elif action < 0:
            reward_do = math.log10(float(self.price/self.next_price)) * amount
            reward_leave = math.log10(1+float(self.interest_rate*30/365)) * (self.stock - amount) * self.price # 可能要改成沒賣的那些股票他們的價值變化
            reward_function_sum = reward_do + reward_leave
            self.reward  = reward_function_sum * self.reward_driver if reward_function_sum >0  else reward_function_sum * self.punish_driver
            #self.reward  = reward_do * self.reward_driver if reward_do >0  else reward_do * self.punish_driver

            #leave_value = (self.stock - amount) * self.next_price
            #next_value = amount * self.price + leave_value
            #this_value = self.price * self.stock
            #reward_function = math.log10(float(this_value/next_value)) 
            #self.reward  = reward_function * self.reward_driver if reward_function >0  else reward_function * self.punish_driver
        
            
        else:
            self.hold = True
        
        if self.length <=0:
            done = True
            info={}
            return self.state, self.reward, done, info
        else:
            done = False
            
        rd +=1
        self.state = self.X[rd]
        self.price = self.X[rd+1][0]
        self.next_price = self.X[rd+2][0]
        info={}
        return self.state, self.reward, done, info
        
    def render(self):
        #Visualization is not work for this project~
        pass
    
    def reset(self): 
        global rd
        self.sell = False
        self.buy = False
        self.hold = False
        self.lastday = 0
        self.asset = []
        self.hold_times= 0
        rd = random.randint(0,len(self.X)-(self._max_episode_steps+3))
        self.state = self.X [rd]
        self.price = self.X[rd+1][0]
        self.next_price = self.X[rd+2][0]
        self.length = self._max_episode_steps
        return self.state