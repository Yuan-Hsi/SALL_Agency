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
                self.X[rd+1][-1] = self.fitting_room(self.sell_maximum)
                self.dumb = 0
                self.reward[0] += action[0]*1.5
                
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
                self.X[rd+1][-1] = self.fitting_room(self.sell_maximum)
                self.reward[0] += -1*action[0]*1.5
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
            self.hold_times +=1
        
        if  self.hold:
            self.X[rd+1][-1] = self.X[rd][-1]
            
            
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
            self.reward[0] += (self.left_money + self.sell_maximum * self.price - self.capital)*2**-7
            info={'action':action}
        else:
            done = False
            tomorrow = self.sell_maximum * self.next_price + self.left_money
            self.reward[0] += -1 * self.dumb*2**-7
        
        
        
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