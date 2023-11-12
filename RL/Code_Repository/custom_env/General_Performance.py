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
        
        #value = np.array(value).reshape(-1, 1)

        #if 'std_scaler' in self.filters:
        #    value = self.filters['std_scaler'].transform(value)
        #if 'norm_scaler' in self.filters:
        #    value = self.filters['norm_scaler'].transform(value)
        #if 'scaler' in self.filters:
        #    value = self.filters['scaler'].transform(value)

        return value
    
    def __init__(self, og_daata, data,space_dict,price_key,reward_driver=1, stock_num = 50000,punish_driver = 3, length = 100, capital = 100000, interest_rate = 0.05,fee_rate=0.02,seed=42,filters={}):
        random.seed(seed)
        X = data
        self.X = data
        self.og_data = og_daata

        price_index = 0
        for key in space_dict:
            if(price_key == key):break
            price_index+=1

        global rd
        rd = 0
        self.price_index = price_index
        self.filters = filters
        self.price = self.og_data[rd][price_index]
        self.next_price = self.og_data[rd+1][price_index]


        """
        加入 buy_maximum, sell_maximum 到 state 中
        """ 
        # buy maximum
        self.buy_maximum = math.floor(capital / self.price)
        self.X[rd][-2] = self.fitting_room(self.buy_maximum)

        # sell maximum
        self.sell_maximum = 0
        self.X[rd][-1] = self.sell_maximum

        self._max_episode_steps = length
        self.state = X[rd]
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
        self.hold_times= 0
        self.left_money = self.capital
        rd = 0

        """
        加入 buy_maximum, sell_maximum 到 state 中
        """ 
        # buy maximum
        self.buy_maximum = math.floor(self.capital / self.price)
        self.X[rd][-2]=self.fitting_room(self.buy_maximum)

        # sell maximum
        self.sell_maximum = 0
        self.X[rd][-1] = self.sell_maximum
        self.state = self.X[rd]
        self.price = self.og_data[rd][self.price_index]
        self.next_price = self.og_data[rd+1][self.price_index]
        self.length = self._max_episode_steps
        return self.state
    

    def step(self, action):
        global rd
        self.length -=1
        self.reward = 0
        self.hold = False

        if action > 0 :
            if self.buy_maximum > 0 :
                amount = math.floor(self.buy_maximum * action)
                if amount>=1:
                    self.left_money -= self.price * amount * (1 + 0.001425) # 加入證交稅
                    self.sell_maximum += amount
                    self.X[rd+1][-1] = self.fitting_room(self.sell_maximum)
                else:
                    self.hold = True
                    #action = np.array([0], dtype ='float64')
            else:
                self.hold = True
                #action = np.array([0], dtype ='float64')
            
        
        elif action < 0:
            if self.sell_maximum > 0 :
                amount = math.floor(abs(self.sell_maximum * action))
                if amount >=1:
                    self.left_money += self.price * amount * (1 - 0.001425 - 0.003) # 加入手續費與證交稅
                    self.sell_maximum -= amount
                    self.X[rd+1][-1] = self.fitting_room(self.sell_maximum)
                else:
                    self.hold = True
                    #action = np.array([0], dtype ='float64')
            else:
                self.hold = True
                #action = np.array([0], dtype ='float64')
        
        if self.hold:
            self.X[rd+1][-1] = self.X[rd][-1] # 將 sell_maximum 保持到下個狀態
        
        if self.length <0:
            done = True
            action = np.array([-1], dtype ='float64')
            info={'action':action}
            self.reward = (self.left_money + self.sell_maximum * self.price - self.capital)/self.capital
            return self.state, self.reward, done, info
        else:
            done = False
            
        rd +=1
        self.price = self.og_data[rd][self.price_index]
        self.next_price = self.og_data[rd+1][self.price_index]
        self.buy_maximum = math.floor(self.left_money / self.price) if self.left_money > 0 else 0
        self.X[rd][-2] = self.fitting_room(self.buy_maximum)
        self.state = self.X[rd]

        info={'action':action}
        return self.state, self.reward, done, info # new_obs, reward, done, info