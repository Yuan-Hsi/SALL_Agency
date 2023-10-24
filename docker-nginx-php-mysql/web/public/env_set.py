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
            reward_leave = math.log10(1+float(self.interest_rate*30/365)) * (self.stock - amount) * self.price
            reward_function_sum = reward_do + reward_leave
            self.reward  = reward_function_sum * self.reward_driver if reward_function_sum >0  else reward_function_sum * self.punish_driver
            
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