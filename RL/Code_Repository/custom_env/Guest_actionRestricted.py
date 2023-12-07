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
        value = value/self.scaler
        return value
    
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
        max_amount = math.floor(capital / self.price)
        n = 10
        total = max_amount
        arr_buy = [0 for i in range(n)]
        arr_sell = [total for i in range(n)]

        for i in range(n):
            if total <= 0 :
                break
            num  = random.randint(1, total)
            arr_buy[i] = num
            total -= arr_buy[i]
            
        random.shuffle(arr_buy)
        arr_sell = list(map(lambda a: a[0]-a[1], zip(arr_sell, arr_buy)))
        

        # buy maximum
        self.X[rd-9:rd+1,-2] = arr_buy
        self.buy_maximum = arr_buy[-1]

        # sell maximum
        self.X[rd-9:rd+1,-1] = arr_sell
        self.sell_maximum = arr_sell[-1]
        self.left_money = capital - self.sell_maximum * self.price

        print("scaler:", self.scaler)
        self._max_episode_steps = length
        self.state = self.X[rd-9:rd+1]
        self.dumb = 0
        self.capital = capital
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
        self.last_action = 0
        self.stumb = 0
        
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
        self.last_action = 0
        self.stumb = 0
        self.asset = []
        self.hold_times= 1
        rd = random.randint(9,len(self.X)-(self._max_episode_steps+2))
        self.dumb = 0
        self.price = self.og_data[rd][self.price_index]
        self.next_price = self.og_data[rd+1][self.price_index]
        """
        加入 buy_maximum, sell_maximum 到 state 中
        """  
        max_amount = math.floor(self.capital / self.price)
        n = 10
        total = max_amount
        arr_buy = [0 for i in range(n)]
        arr_sell = [total for i in range(n)]

        for i in range(n):
            if total <= 0 :
                break
            num  = random.randint(1, total)
            arr_buy[i] = num
            total -= arr_buy[i]
            
        random.shuffle(arr_buy)
        arr_sell = list(map(lambda x: x[0]-x[1], zip(arr_sell, arr_buy)))

        # buy maximum
        self.X[rd-9:rd+1,-2] = arr_buy
        self.buy_maximum = arr_buy[-1]

        # sell maximum
        self.X[rd-9:rd+1,-1] = arr_sell
        self.sell_maximum = arr_sell[-1]
        self.left_money = self.capital - self.sell_maximum * self.price

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
        Given the left money can buy "a" stock at least, you can give a reward calculation below.
        If the left money is not enogh for a stock, but the signal is given. YOU CAN GIVE THE AGENT A PENALTY. 
        As you can  see in the example.
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
                self.hold = True
                self.dumb +=10
                 # example penalty: self.reward[0] = abs(self.price - self.next_price) * -action[0]

            if self.last_action > 0.01 :
                self.stumb +=1
            else : 
                self.stumb = 0 
            
        """
        ------------------- The buying action end here

        CODE for the selling action start here ----------------
        When the action smaller than -0.1, it's a selling signal. (you can set the selling signal in other number, as well.)
        Given the "a" stock you have at least, you can give a reward calculation below.
        If run out of stock, but the signal is given. YOU CAN GIVE THE AGENT A PENALTY.
        As you can  see in the example.
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
            else:
                # example penalty: self.reward[0] = abs(self.price - self.next_price)  * action[0]
                self.hold = True
                self.dumb +=10

            if self.last_action < -0.01 :
                self.stumb +=1
            else:
                self.stumb = 0
        
        """
        --------------- The selling action end here

        CODE for the hold action start here ----------------
        If you want to encourage the agent hold, you can give the agent some reward.
        """  
        
        if action > -0.01 and action < 0.01:
            self.hold = True
            self.dumb = 0 
            if self.last_action > -0.01 and self.last_action < 0.01:
                self.stumb += 1
            else:
                self.stumb = 0
        
        if  self.hold:
            self.X[rd+1][-1] = self.X[rd][-1]
            self.hold_times +=1
            
        
        """
        --------------- The hold action end here

        The episode end ------------------------
        Some researcher will give their agent reward only when the episode end like the total invest profit.
        As you can see in the example.
        """
        if self.length <=0:
            done = True
            action = np.array([-1], dtype ='float64')
            self.reward[0] = ((self.left_money + self.sell_maximum * self.price - self.capital) / self.capital)
            info={'action':action}
        else:
            done = False
            tomorrow = self.sell_maximum * self.next_price + self.left_money
            self.reward[0] = (tomorrow - before_action) * 2 ** -11
        
        #if self.stumb > 5:
            #self.reward[0] += -1 * self.stumb * 2 ** -10
        self.reward[0] += -1 * self.dumb * 2 ** -6
        
        """
        end -------------------------------------
        """
        
        rd +=1
        self.price = self.og_data[rd][self.price_index]
        self.next_price = self.og_data[rd+1][self.price_index]
        self.buy_maximum = math.floor(self.left_money / self.price) if self.left_money > 0 else 0
        self.X[rd][-2] = self.buy_maximum
        self.state = self.X[rd-9:rd+1]
        self.last_action = action

        info={'action':action}
        return self.state, self.reward, done, info # new_obs, reward, done, info