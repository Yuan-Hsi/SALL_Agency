from gym import Env, spaces
from gym.spaces import Discrete, Box, Dict
import pandas as pd
import numpy as np
import random
import math
from sklearn.preprocessing import StandardScaler
from sklearn.preprocessing import Normalizer
from sklearn.preprocessing import MinMaxScaler

class ETFenv(Env):
    
    def fitting_room(self,value):
        # 检查字典是否为空
        if not self.filters:
            return value
        else:
            return value/self.scaler
    
    def __init__(self, og_daata, data,space_dict,price_key,reward_driver=1, stock_num = 50000, punish_driver = 3, length = 100, capital = 100000, interest_rate = 0.05,fee_rate=0.02,seed=42,filters={}):
        random.seed(seed)
        X = data
        self.X = data
        self.og_data = og_daata

        price_index = 0
        for key in space_dict:
            if(price_key == key):break
            price_index+=1

        global rd
        rd = random.randint(9,len(X)-(length+2))
        self.price_index = price_index
        self.filters = filters
        self.price = self.og_data[rd][price_index]
        self.next_price = self.og_data[rd+1][price_index]
        self.scaler = capital/np.min(self.og_data [:,self.price_index])

        """
        加入 buy_maximum, sell_maximum 到 state 中
        """ 
        # buy maximum
        self.buy_maximum = math.floor(capital / self.price)
        self.X[rd-9:rd+1,-2] = self.fitting_room(self.buy_maximum)

        # sell maximum
        self.sell_maximum = 0
        self.X[rd-9:rd+1,-1] = self.sell_maximum

        print("scaler:", self.scaler)
        self._max_episode_steps = length
        self.state = self.X[rd-9:rd+1]
        self.dumb = 0
        self.capital = capital
        self.left_money = capital
        self.interest_rate = interest_rate
        #set length
        self.length = self._max_episode_steps
        #set holding
        self.reward_driver = reward_driver
        self.punish_driver = punish_driver
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
        self.hold_times= 1
        self.left_money = self.capital
        rd = random.randint(9,len(self.X)-(self._max_episode_steps+2))
        self.price = self.og_data[rd][self.price_index]

        """
        加入 buy_maximum, sell_maximum 到 state 中
        """ 
        # buy maximum
        self.buy_maximum = math.floor(self.capital / self.price)
        self.X[rd-9:rd+1,-2]=self.fitting_room(self.buy_maximum)

        # sell maximum
        self.sell_maximum = 0
        self.dumb = 0
        self.X[rd-9:rd+1,-1] = self.sell_maximum
        self.state = self.X[rd-9:rd+1]
        self.next_price = self.og_data[rd+1][self.price_index]
        self.length = self._max_episode_steps
        return self.state
    