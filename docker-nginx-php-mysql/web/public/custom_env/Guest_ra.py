    def step(self, action):
        global rd
        self.length -=1
        self.reward = np.array([0],dtype ='float64')
        self.hold = False
        
        if action > 0.1 :
            amount = math.floor(self.buy_maximum * action)
            if amount >=1:
                self.left_money -= self.price * amount * (1 + 0.001425) # plus the tax fee
                self.sell_maximum += amount
                self.X[rd+1][-1] = self.fitting_room(self.sell_maximum)
                self.reward[0] = float(amount * (self.next_price - self.price) + 10 *  self.hold_times) if (self.next_price - self.price) > 0 else 0 
                #self.reward[0] = float(amount * self.hold_times)
                self.hold_times = 1
                self.dumb = 0
            else:
                self.hold = True
                self.dumb+= 5 * action[0]
                self.reward[0] = float(-1.0  * self.dumb ** 3)
        
        elif action < -0.1:
            amount = math.floor(abs(self.sell_maximum * action))
            if amount >=1:
                self.left_money += self.price * amount * (1 - 0.001425 - 0.003) # plus the tax fee and commission charge
                self.sell_maximum -= amount
                self.X[rd+1][-1] = self.fitting_room(self.sell_maximum)
                self.reward[0] = float(amount * (self.price - self.next_price) + 10 * self.hold_times) if (self.price - self.next_price) > 0 else 0 
                #self.reward[0] = float(amount * self.hold_times)
                self.hold_times = 1
                self.dumb = 0
            else:
                self.hold = True
                self.dumb += -5 * action[0]
                self.reward[0] = float(-1.0  * self.dumb ** 3)

        else:
            self.hold = True
            self.hold_times *= 2
            self.dumb = 0
            
        if  self.hold:
            self.X[rd+1][-1] = self.X[rd][-1] # keep sell_maximum to the next state
            
        
        if self.length <=0:
            done = True
            #action = np.array([0], dtype ='float64')
            info={'action':action}
            #profit = self.left_money + self.sell_maximum * self.price - self.capital
            #self.reward[0] = profit * (1+0.01*self.hold_times) if profit >0 else profit
            self.hold_times = 0
        else:
            done = False
            
        rd +=1
        self.price = self.og_data[rd][self.price_index]
        self.next_price = self.og_data[rd+1][self.price_index]
        self.buy_maximum = math.floor(self.left_money / self.price) if self.left_money > 0 else 0
        self.X[rd][-2] = self.fitting_room(self.buy_maximum)
        self.state = self.X[rd-9:rd+1]

        info={'action':action}
        return self.state, self.reward, done, info # new_obs, reward, done, info