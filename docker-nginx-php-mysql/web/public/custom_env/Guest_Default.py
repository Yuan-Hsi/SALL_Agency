    def step(self, action):
        global rd
        self.length -=1
        self.reward = np.array([0],dtype ='float64')
        self.hold = False
        amount = 0
        before_action = self.sell_maximum * self.price + self.left_money


        if action > 0.01 :
            amount = math.floor(self.buy_maximum * action)
            if amount >=1:
                self.left_money -= self.price * amount * (1 + 0.001425)
                self.sell_maximum += amount
                self.X[rd+1][-1] = self.fitting_room(self.sell_maximum)
                self.dumb = 0
                self.reward[0] += action[0] 
                
            # The scenerio can not buy a stock
            else:
                self.dumb +=1
                self.hold = True
            

        if action < -0.01:
            amount = math.floor(abs(self.sell_maximum * action))
            if amount >=1:
                self.left_money += self.price * amount * (1 - 0.001425 - 0.003) 
                self.sell_maximum -= amount
                self.X[rd+1][-1] = self.fitting_room(self.sell_maximum)
                self.reward[0] += -1 * action[0] 
                self.hold_times = 1
                self.dumb = 0
            # The scenerio can not buy a stock
            else:
                self.dumb +=1
                self.hold = True
        
        if action > -0.01 and action < 0.01:
            self.dumb = 0
            self.hold = True
            self.hold_times +=1
        
        if  self.hold:
            self.X[rd+1][-1] = self.X[rd][-1]
            
            
        if self.hold_times > 5:
            self.dumb = self.hold_times
        

        if self.length <=0:
            done = True
            self.reward[0] += (self.left_money + self.sell_maximum * self.price - self.capital) * 2 ** -7
            info={'action':action}
        else:
            done = False
            tomorrow = self.sell_maximum * self.next_price + self.left_money
            self.reward[0] += -1 * self.dumb * 2 ** 2
            
            
        rd +=1
        self.price = self.og_data[rd][self.price_index]
        self.next_price = self.og_data[rd+1][self.price_index]
        self.buy_maximum = math.floor(self.left_money / self.price) if self.left_money > 0 else 0
        self.X[rd][-2] = self.fitting_room(self.buy_maximum)
        self.state = self.X[rd-9:rd+1]

        info={'action':action}
        return self.state, self.reward, done, info # new_obs, reward, done, info