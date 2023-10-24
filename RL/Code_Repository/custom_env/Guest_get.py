from gym import Env, spaces
from gym.spaces import Discrete, Box, Dict
import pandas as pd
import numpy as np
import random
import math
from sklearn import preprocessing


class ETFenv(Env):
    
    def __init__(self, data,space_dict,price_key,reward_driver=1, punish_driver = 3, length = 100, stock_num = 1000, interest_rate = 0.05,fee_rate=0.02,seed=42):
        random.seed(seed)
        X = data
        self.X = data

        price_index = 0
        for key in space_dict:
            if(price_key == key):break
            price_index+=1

        self.price_index = price_index

        global rd
        rd = random.randint(0,len(X)-(length+3))

        self._max_episode_steps = length
        self.state = X[rd]
        self.price = self.X[rd+1][price_index]
        self.next_price = self.X[rd+2][price_index]
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
        self.observation_space = spaces.Dict(space_dict)
        
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
        self.price = self.X[rd+1][self.price_index]
        self.next_price = self.X[rd+2][self.price_index]
        self.length = self._max_episode_steps
        return self.state
    

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
        self.price = self.X[rd+1][self.price_index]
        self.next_price = self.X[rd+2][self.price_index]
        info={'action':action}
        return self.state, self.reward, done, info