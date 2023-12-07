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
        self.scaler = capital/np.max(self.og_data [:,self.price_index])

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

        """
        加入 buy_maximum, sell_maximum 到 state 中
        """ 
        # buy maximum
        self.buy_maximum = math.floor(self.capital / self.price)
        self.X[rd-9:rd+1][-2]=self.fitting_room(self.buy_maximum)

        # sell maximum
        self.sell_maximum = 0
        self.dumb = 0
        self.X[rd-9:rd+1,-1] = self.sell_maximum
        self.state = self.X[rd-9:rd+1]
        self.price = self.og_data[rd][self.price_index]
        self.next_price = self.og_data[rd+1][self.price_index]
        self.length = self._max_episode_steps
        return self.state
    

    def step(self, action):

        """
        Welcome to SALL_Agency's reward lab !!
        Make sure you have read the gray area above.
        In this section you can change the reward in the way you think which can let the agent improve.
        Or you can use the scroll above to change to the default agent .

        We also have some feature you must to know:
        * Rules Validation - This help your code can be test for a whole run in the data you selected when you press SAVE.
        * Share with your team - In the scrool beyond this section, you are copy other agent's reward thinking and edit in your own agent.

        PLEASE NOTICE : THE REWARD AND ANCTION VALUE IS IN NP.FLOAT64[0], MAKE SURE YOU EDIT IT IN THE RIGHT WAY. 
        """

        global rd
        self.length -=1
        self.reward = np.array([0],dtype ='float64')
        self.hold = False
        amount = 0
        before_action = self.sell_maximum * self.price + self.left_money

        """
        CODE for the buying action start here ----------------
        When the action bigger than 0.1, it's a buying signal. (you can set the buying signal in other number, as well.)
        """
        if action > 0.01 :
            amount = math.floor(self.buy_maximum * action)
            if amount >=1:
                self.left_money -= self.price * amount * (1 + 0.001425)
                self.sell_maximum += amount
                self.X[rd+1][-1] = self.sell_maximum
                self.dumb = 0
                # example reward: self.reward[0] = amount * (self.next_price - self.price) *  self.hold_times if (self.price - self.next_price) > 0 else 0
                
            # The scenerio can not buy a stock
            else:
                self.dumb +=1
                self.hold = True
            
        """
        CODE for the selling action start here ----------------
        When the action smaller than -0.1, it's a selling signal. (you can set the selling signal in other number, as well.)
        """      
        if action < -0.01:
            amount = math.floor(abs(self.sell_maximum * action))
            if amount >=1:
                self.left_money += self.price * amount * (1 - 0.001425 - 0.003) 
                self.sell_maximum -= amount
                self.X[rd+1][-1] = self.sell_maximum
                # example reward: self.reward[0] = amount * (self.price - self.next_price) *  self.hold_times  if (self.price - self.next_price) > 0 else 0
                self.hold_times = 1
                self.dumb = 0
            # The scenerio can not buy a stock
            else:
                self.dumb +=1
                self.hold = True
        
        """
        CODE for the hold action start here ----------------
        If you want to encourage the agent hold, you can give the agent some reward.
        """  
        
        if action > -0.01 and action < 0.01:
            self.dumb = 0
            self.hold = True
        
        if  self.hold:
            self.X[rd+1][-1] = self.X[rd][-1]
            self.hold_times +=1
            
        if self.hold_times > 5:
            self.dumb = self.hold_times
        
        """
        The episode end ------------------------
        Some researcher will give their agent reward only when the episode end like the total invest profit.
        As you can see in the example.
        """
        if self.length <=0:
            done = True
            #action = np.array([-1], dtype ='float64')
            self.reward[0] = (self.left_money + self.sell_maximum * self.price - self.capital) * 2 ** -8
            info={'action':action}
        else:
            done = False
            tomorrow = self.sell_maximum * self.next_price + self.left_money
            self.reward[0] = -1 * self.dumb * 2 **-3
            # self.reward[0] += (tomorrow - before_action) * 2 ** -10
        
        
        
        """
        end -------------------------------------
        """
            
        rd +=1
        self.price = self.og_data[rd][self.price_index]
        self.next_price = self.og_data[rd+1][self.price_index]
        self.buy_maximum = math.floor(self.left_money / self.price) if self.left_money > 0 else 0
        self.X[rd][-2] = self.fitting_room(self.buy_maximum)
        self.state = self.X[rd-9:rd+1]

        info={'action':action}
        return self.state, self.reward, done, info # new_obs, reward, done, info