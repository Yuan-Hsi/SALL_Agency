    def step(self, action):
        global rd
        self.length -=1
        self.reward = 0
        self.hold = False
        
       
        if action > 0 :
            if self.buy_maximum > 1 :
                amount = math.floor(self.buy_maximum * action)
                if amount >=1:
                    self.left_money -= self.price * amount * (1 + 0.001425) # plus the tax fee
                    self.sell_maximum += amount
                    self.X[rd+1][-1] = self.fitting_room(self.sell_maximum)
                    #self.reward = amount * (self.next_price - self.price) if (self.next_price - self.price) > 0 else 0
                else:
                    self.hold = True
            else:
                self.hold = True
            
        
        elif action < 0:
            if self.sell_maximum > 1 :
                amount = math.floor(abs(self.sell_maximum * action))
                if amount >=1:
                    self.left_money += self.price * amount * (1 - 0.001425 - 0.003) # plus the tax fee and commission charge
                    self.sell_maximum -= amount
                    self.X[rd+1][-1] = self.fitting_room(self.sell_maximum)
                    #self.reward = amount * (self.price - self.next_price) *  self.hold_times  if (self.price - self.next_price) > 0 else 0
                    self.hold_times = 1
                else:
                    #self.reward = abs(self.price - self.next_price) * 100 * action[0]
                    self.hold = True
            else:
                #self.reward = abs(self.price - self.next_price)  * 100 * action[0]
                self.hold = True

        else:
            self.hold = True
            
            
        if  self.hold:
            self.X[rd+1][-1] = self.X[rd][-1] # keep sell_maximum to the next state
            self.hold_times +=1
            
        
        if self.length <=0:
            done = True
            action = np.array([-1], dtype ='float64')
            info={'action':action}
            self.reward = self.left_money + self.sell_maximum * self.price - self.capital
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