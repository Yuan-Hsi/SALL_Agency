from gym import Env, spaces
from gym.spaces import Discrete, Box, Dict
import pandas as pd
import numpy as np
import random
import math

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
    
    def __init__(self, id = "0050"):

        address = "../File_Repository/Train set/" + id + "_train.csv"
        dataset = pd.read_csv(address)
        X = dataset.iloc[:, 1:].values
        self.X = MACD(list_input = X)

        global rd
        rd = random.randint(0,len(X)-101)

        self._max_episode_steps = 100
        self.state = X[rd]
        self.price = self.state[0]
        #set length
        self.length = 100
        #set holding
        self.holdstock = 0
        self.holdmoney = 100000
        self.hold_times=0
        self.reward = 0
        self.asset = []
        self.lastday = 0
        self.hold = False
        self.buy = False
        self.sell = False
        
        #Action Buy(0),Sell(1),Hold(2)
        self.action_space = Box(low = -(self.holdstock), high= (self.holdmoney/self.price), shape=(1,), dtype=np.int)
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
        

        if self.holdmoney > 0 and action > 0:
            amount = math.floor(action)
            if amount > 0:
                self.holdstock += amount
                self.holdmoney -= self.price * amount
                self.hold_times = 0
            else :
                self.hold = True
        
        
        elif self.holdstock > 0 and action < 0  :
            amount = math.floor(action)
            self.holdmoney += self.price * amount * -1
            self.holdstock += amount
            self.hold_times = 0
            
        else:
            self.hold = True
         
        
        self.asset.append(float(self.holdstock * self.price + self.holdmoney))
        if len(self.asset)>2 :
            try:
                reward_func = math.log10(self.asset[1]/self.asset[0]) *200
                if reward_func < 0:
                    reward_func *= 1.5
                self.reward = reward_func  #原本：self.reward = math.log10(self.asset[1]/self.asset[0])*100
                del self.asset[0]
            except:
                self.reward = 0
        else:
            self.reward = 0
            
        
        if self.length <=0:
            done = True
        else:
            done = False
            
        rd +=1
        self.state = self.X[rd]
        self.price = self.state[0]
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
        self.holdstock = 0
        self.holdmoney = 100000
        self.asset = []
        self.hold_times= 0
        rd = random.randint(0,len(self.X)-101)
        self.state = self.X [rd]
        self.price = self.state[0]
        self.length = 100
        return self.state