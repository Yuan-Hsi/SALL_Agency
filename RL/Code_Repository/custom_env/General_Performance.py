from gym import Env, spaces
from gym.spaces import Discrete, Box, Dict
import pandas as pd
import numpy as np
import random
import math
from sklearn import preprocessing


class ETFenv(Env):
    
    def __init__(self, data,space_dict,price_key,reward_driver=1, punish_driver = 3, length = 100, stock_num = 1000, interest_rate = 0.05,fee_rate=0.02,seed=42):
        X = data
        self.X = data
        global rd
        rd = 0
        
        price_index = 0
        for key in space_dict:
            if(price_key == key):break
            price_index+=1

        self.price_index = price_index

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
        rd = 0
        self.sell = False
        self.buy = False
        self.hold = False
        self.lastday = 0
        self.asset = []
        self.hold_times= 0
        self.state = self.X[rd]
        self.price = self.X[rd+1][self.price_index]
        self.next_price = self.X[rd+2][self.price_index]
        self.length = self._max_episode_steps
        return self.state