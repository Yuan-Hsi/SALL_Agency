import os
import time
import random
import numpy as np
import matplotlib.pyplot as plt
import gym
import torch
import torch.nn as nn
import torch.nn.functional as F
from gym import wrappers
from gym.spaces import Discrete, Box, Dict
from torch.autograd import Variable
from collections import deque
from torch.nn.init import kaiming_uniform_
import pandas as pd


# Selecting the device (CPU or GPU)
print("cuda is on") if torch.cuda.is_available() else print("cuda is off")
device = torch.device("cuda" if torch.cuda.is_available() else "cpu")
os.environ["CUBLAS_WORKSPACE_CONFIG"] = ":16:8"

class ReplayBuffer(object):

    def __init__(self, max_size=1e6, seed=42):
        self.storage = []
        self.bask = []
        self.max_size = max_size
        self.ptr = 0
        np.random.seed(seed)

    def add(self, transition):
        if len(self.storage) == self.max_size:
            self.storage[int(self.ptr)] = transition
            self.ptr = (self.ptr + 1) % self.max_size
        else:
            self.storage.append(transition)

    def sample(self, batch_size):
        if (len(self.storage) > 5000):
            random_size = int(batch_size * 2 / 3)
            recent_size = int(batch_size / 3)
            ind = np.random.randint(0, len(self.storage), size=random_size)
            recent_ind = np.random.randint(1, 5000, size=recent_size)
        else:
            ind = np.random.randint(0, len(self.storage), size=int(batch_size))
            recent_ind = []
        batch_states, batch_next_states, batch_actions, batch_rewards, batch_dones = [], [], [], [], []
        for i in ind: 
            state, next_state, action, reward, done = self.storage[i]
            batch_states.append(np.array(state, copy=False))
            batch_next_states.append(np.array(next_state, copy=False))
            batch_actions.append(np.array(action, copy=False))
            batch_rewards.append(np.array(reward, copy=False))
            batch_dones.append(np.array(done, copy=False))
        for j in recent_ind:
            state, next_state, action, reward, done = self.storage[-j]
            batch_states.append(np.array(state, copy=False))
            batch_next_states.append(np.array(next_state, copy=False))
            batch_actions.append(np.array(action, copy=False))
            batch_rewards.append(np.array(reward, copy=False))
            batch_dones.append(np.array(done, copy=False))   

        self.bask.append(ind)       
        return np.array(batch_states), np.array(batch_next_states), np.array(batch_actions), np.array(batch_rewards).reshape(-1, 1), np.array(batch_dones).reshape(-1, 1)
    
    def output(self):
        #df = pd.DataFrame(self.bask[-5:])
        #df.to_csv('../File_Repository/test3/data.csv', index=False)
        #df2 = pd.DataFrame(self.storage)
        #df2.to_csv('../File_Repository/test3/data2.csv', index=False)
        return self.bask[0]


class Actor(nn.Module):

      
    def initialize_weights(self,model):
        for name, param in model.named_parameters():
            if 'weight' in name:
                if 'linear' in name:  # 初始化线性层的权重
                    torch.nn.init.xavier_normal_(param)  # 使用Xavier初始化方法
                else:
                    torch.nn.init.kaiming_normal_(param)  # 使用Kaiming初始化方法
            elif 'bias' in name:
                torch.nn.init.constant_(param, 0.0)  # 将偏置参数初始化为0
  
    def __init__(self, state_dim, action_dim, max_action,seed):
        torch.manual_seed(seed)
        torch.cuda.manual_seed(seed)
        torch.cuda.manual_seed_all(seed)
        torch.set_deterministic(True)
        torch.backends.cudnn.deterministic = True
        torch.backends.cudnn.benchmark = False

        super(Actor, self).__init__()
        self.hidden_size = 400
        
        
        self.lstm = nn.LSTM(input_size=state_dim[1], hidden_size=self.hidden_size, num_layers=1, batch_first=True)
        #nn.init.uniform_(self.lstm.weight_ih_l0, -0.01, 0.01)
        #nn.init.orthogonal_(self.lstm.weight_hh_l0)

        #self.layer_1 = nn.Linear(200*state_dim[0], 400)
        self.layer_test = nn.Linear(self.hidden_size, 300)
        #nn.init.normal_(self.layer_test.weight, mean=0.0, std=0.1)

        #self.ln1 = nn.LayerNorm(400)
        #self.layer_2 = nn.Linear(400, 150)
        #self.ln2 = nn.LayerNorm(300)
        self.layer_3 = nn.Linear(300, action_dim)
        #nn.init.normal_(self.layer_3.weight, mean=0.0, std=0.01)
        self.max_action = max_action
        """
        self.lstm = nn.LSTM(input_size=state_dim[1]-2, hidden_size=self.hidden_size, num_layers=1, batch_first=True)
        self.layer_test = nn.Linear(self.hidden_size+2, 300)
        self.layer_3 = nn.Linear(300, action_dim)
        """
        self.max_action = max_action

        for name, param in self.lstm.named_parameters():
            if 'weight' in name:
                if 'lstm' in name:  # 初始化LSTM层的权重
                    torch.nn.init.kaiming_normal_(param)  # 使用Kaiming初始化方法
                else:
                    torch.nn.init.xavier_normal_(param)  # 使用Xavier初始化方法
            elif 'bias' in name:
                torch.nn.init.constant_(param, 0.0)  # 将偏置参数初始化为0

        self.initialize_weights(self.layer_test)
        self.initialize_weights(self.layer_3)
        #torch.nn.init.xavier_normal_(self.layer_test.weight) 
        #torch.nn.init.xavier_normal_(self.layer_3.weight) 

    def forward(self, x):

        #state = x[:,-1,-2:] #!
        # x =  x[:,:,:-2] #!
        x, _ = self.lstm(x)
        #x = x.reshape(x.shape[0], -1)
        #x = F.relu(self.layer_1(x)) # Relu
        
        x = x[:, -1, :]
        #x = torch.cat([x,state],1) #!
        x =  F.relu(self.layer_test(x))
        #x = self.ln1(x)
        #x = F.relu(self.layer_2(x)) # Relu
        #x = self.ln2(x)
        x = nn.Tanh()(self.layer_3(x))
        return x
    
    def evaluate(self,x):
        x = x.unsqueeze(0)
        #state = x[:,-1,-2:] #!
        #x =  x[:,:,:-2] #!
        #x, _ = self.lstm(x.unsqueeze(0))
        x, _ = self.lstm(x)
        x = x[:, -1, :]
        #x = torch.cat([x,state],1) #!
        #x = x.flatten()
        #x = F.relu(self.layer_1(x)) # Relu
        x = F.relu(self.layer_test(x))
        #x = self.ln1(x)
        #x = F.relu(self.layer_2(x)) # Relu
        #x = self.ln2(x)
        x = nn.Tanh()(self.layer_3(x))
        return x        
    
class Critic(nn.Module):
    
    def initialize_weights(self,model):
        for name, param in model.named_parameters():
            if 'weight' in name:
                if 'linear' in name:  # 初始化线性层的权重
                    torch.nn.init.xavier_normal_(param)  # 使用Xavier初始化方法
                else:
                    torch.nn.init.kaiming_normal_(param)  # 使用Kaiming初始化方法
            elif 'bias' in name:
                torch.nn.init.constant_(param, 0.0)  # 将偏置参数初始化为0

    def __init__(self, state_dim, action_dim,seed):
        torch.manual_seed(seed)
        torch.cuda.manual_seed(seed)
        torch.cuda.manual_seed_all(seed)
        torch.set_deterministic(True)
        torch.backends.cudnn.deterministic = True
        torch.backends.cudnn.benchmark = False

        super(Critic, self).__init__()
                # Defining the first Critic neural network
        self.hidden_size = 400

        
        self.lstm = nn.LSTM(input_size=state_dim[1], hidden_size=self.hidden_size, num_layers=1, batch_first=True)
        #self.layer_1 = nn.Linear(self.hidden_size*state_dim[0] + action_dim, 400)
        self.layer_test = nn.Linear(self.hidden_size+ action_dim, 300)
        #self.ln1 = nn.LayerNorm(400)
        #self.layer_2 = nn.Linear(400, 300)
        #self.ln2 = nn.LayerNorm(300)
        self.layer_3 = nn.Linear(300, 1)
        # Defining the second Critic neural network
        #self.layer_4 = nn.Linear(self.hidden_size*state_dim[0] + action_dim, 400)
        self.layer_test2 = nn.Linear(self.hidden_size+ action_dim, 300)
        #self.ln3 = nn.LayerNorm(400)
        #self.layer_5 = nn.Linear(400, 300)
        #self.ln4 = nn.LayerNorm(300)
        self.layer_6 = nn.Linear(300, 1)
        """

        self.lstm = nn.LSTM(input_size=state_dim[1]-2, hidden_size=self.hidden_size, num_layers=1, batch_first=True)
        self.layer_test = nn.Linear(self.hidden_size+ action_dim + 2, 300)
        self.layer_3 = nn.Linear(300, 1)
        self.layer_test2 = nn.Linear(self.hidden_size+ action_dim + 2, 300)
        self.layer_6 = nn.Linear(300, 1)

        """
        #nn.init.xavier_uniform_(self.lstm.weight_ih_l0)
        for name, param in self.lstm.named_parameters():
            if 'weight' in name:
                if 'lstm' in name:  # 初始化LSTM层的权重
                    torch.nn.init.kaiming_uniform_(param)  # 使用Kaiming初始化方法
                else:
                    torch.nn.init.xavier_normal_(param)  # 使用Xavier初始化方法
            elif 'bias' in name:
                torch.nn.init.constant_(param, 0.0)  # 将偏置参数初始化为0

    
        self.initialize_weights(self.layer_test)
        self.initialize_weights(self.layer_3)
        self.initialize_weights(self.layer_test2)
        self.initialize_weights(self.layer_6)
        #torch.nn.init.kaiming_normal_(self.layer_test.weight) 
        #torch.nn.init.kaiming_normal_(self.layer_3.weight) 
        #torch.nn.init.kaiming_normal_(self.layer_test2.weight) 
        #torch.nn.init.kaiming_normal_(self.layer_6.weight) 


        #kaiming_uniform_(self.layer_1.weight)
        #kaiming_uniform_(self.layer_2.weight)
        #kaiming_uniform_(self.layer_3.weight)

        #kaiming_uniform_(self.layer_4.weight) 
        #kaiming_uniform_(self.layer_5.weight)
        #kaiming_uniform_(self.layer_6.weight)

    def forward(self, x, u):
        #state = x[:,-1,-2:] #!
        #x =  x[:,:,:-2] #!
        before_ls = x
        x, _ = self.lstm(x)
        x = x[:, -1, :]
        #x = x.reshape(x.shape[0], -1)
        
        xu = torch.cat([x, u], 1) #!! add state don't del all
        # Forward-Propagation on the first Critic Neural Network
        #x1 = F.relu(self.layer_1(xu))
        x1 = F.relu(self.layer_test(xu))
        #x1 = self.ln1(x1)
        #x1 = F.relu(self.layer_2(x1))
        #x1 = self.ln2(x1)
        x1 = self.layer_3(x1)
        # Forward-Propagation on the second Critic Neural Network
        x2 = F.relu(self.layer_test2(xu))
        #x2 = self.ln3(x2)
        #x2 = F.relu(self.layer_5(x2))
        #x2 = self.ln4(x2)
        x2 = self.layer_6(x2)

        return x1, x2

    def Q1(self, x, u):
        #state = x[:,-1,-2:] #!
        #x =  x[:,:,:-2] #!
        x, _ = self.lstm(x)
        x = x[:, -1, :]
        #x = x.reshape(x.shape[0], -1)
        xu = torch.cat([x, u], 1)#!! add state don't del all
        #x1 = F.relu(self.layer_1(xu))
        x1 = F.relu(self.layer_test(xu))
        #x1 = F.relu(self.layer_2(x1))
        x1 = self.layer_3(x1)
        return x1
    
class TD3(object):
  
    def __init__(self, state_dim, action_dim, max_action,seed,actor_lr,target_lr):
        torch.manual_seed(seed)
        torch.cuda.manual_seed(seed)
        torch.cuda.manual_seed_all(seed)
        torch.set_deterministic(True)
        torch.backends.cudnn.deterministic = True
        torch.backends.cudnn.benchmark = False

        self.actor = Actor(state_dim, action_dim, max_action,seed).to(device)
        self.actor_target = Actor(state_dim, action_dim, max_action,seed).to(device)
        self.actor_target.load_state_dict(self.actor.state_dict())
        self.actor_optimizer = torch.optim.Adam(self.actor.parameters(),lr=1* 10.0 ** -actor_lr,weight_decay=1) # 
        
        self.critic = Critic(state_dim, action_dim,seed).to(device)
        self.critic_target = Critic(state_dim, action_dim,seed).to(device)
        self.critic_target.load_state_dict(self.critic.state_dict())
        self.critic_optimizer = torch.optim.SGD(self.critic.parameters(),lr=1 * 10.0 ** -target_lr,weight_decay=1) # 

        self.max_action = max_action
        self.seed = seed
        self.collect_baseket = []

    def delete_cache(self):
        del self.actor
        del self.actor_target
        del self.critic
        del self.critic_target

        return 'complete'

    def evaluate_action(self, state):
        #state = torch.Tensor(state.reshape(1, -1)).to(device)
        state = torch.Tensor(state).to(device)
        return self.actor.evaluate(state).cpu().data.numpy().flatten()

    def select_action(self, state):
        #state = torch.Tensor(state.reshape(1, -1)).to(device)
        state = torch.Tensor(state).to(device)
        return self.actor(state).cpu().data.numpy().flatten()

    def train(self, replay_buffer, iterations, batch_size=100, discount=0.99, tau=0.005, policy_noise=0.2, noise_clip=0.5, policy_freq=2):
        torch.manual_seed(self.seed)
        torch.cuda.manual_seed(self.seed)
        torch.cuda.manual_seed_all(self.seed)
        

        for it in range(iterations):
            
            # Step 4: We sample a batch of transitions (s, s’, a, r) from the memory
            batch_states, batch_next_states, batch_actions, batch_rewards, batch_dones = replay_buffer.sample(batch_size)
            self.collect_baseket.append(batch_states)
            state = torch.Tensor(batch_states).to(device)
            next_state = torch.Tensor(batch_next_states).to(device)
            action = torch.Tensor(batch_actions).to(device)
            reward = torch.Tensor(batch_rewards).to(device)
            done = torch.Tensor(batch_dones).to(device)
            """
            if(torch.isnan(state).any()):
                print('step 4 state has nan')
                break
            if(torch.isnan(next_state).any()):
                print('step 4 next_state has nan')
                break
            if(torch.isnan(action).any()):
                print('step 4 action has nan')
                break
            if(torch.isnan(reward).any()):
                print('step 4 reward has nan')
                break
            if(torch.isnan(done).any()):
                print('step 4 done has nan')
                break
            """
            
            # Step 5: From the next state s’, the Actor target plays the next action a’
            next_action = self.actor_target(next_state)
            
            """
            if(torch.isnan(next_action).any()):
                print('step 5 has nan')
                break
            """

            # Step 6: We add Gaussian noise to this next action a’ and we clamp it in a range of values supported by the environment
            noise = torch.Tensor(batch_actions).data.normal_(0, policy_noise).to(device)
            noise = noise.clamp(-noise_clip, noise_clip)
            next_action = (next_action + noise).clamp(-self.max_action, self.max_action)
            """
            if(torch.isnan(next_action).any()):
                print('step 6 has nan')
                break
            """
             
            # Step 7: The two Critic targets take each the couple (s’, a’) as input and return two Q-values Qt1(s’,a’) and Qt2(s’,a’) as outputs
            target_Q1, target_Q2 = self.critic_target(next_state, next_action)
            """
            if(torch.isnan(target_Q1).any() or torch.isnan(target_Q2).any() ):
                print('step 7 has nan')
                break
            """

            # Step 8: We keep the minimum of these two Q-values: min(Qt1, Qt2)
            target_Q = torch.min(target_Q1, target_Q2)
            

            # Step 9: We get the final target of the two Critic models, which is: Qt = r + γ * min(Qt1, Qt2), where γ is the discount factor
            target_Q = reward + ((1 - done) * discount * target_Q).detach()
            """
            if(torch.isnan(target_Q).any()):
                print('step 9 has nan')
                break
            """
            
            # Step 10: The two Critic models take each the couple (s, a) as input and return two Q-values Q1(s,a) and Q2(s,a) as outputs
            current_Q1, current_Q2 = self.critic(state, action)
            if(torch.isnan(current_Q1).any() or torch.isnan(current_Q2).any()):
                print('step 10 has nan')
                break
            
            # Step 11: We compute the loss coming from the two Critic models: Critic Loss = MSE_Loss(Q1(s,a), Qt) + MSE_Loss(Q2(s,a), Qt)
            critic_loss = F.mse_loss(current_Q1, target_Q) + F.mse_loss(current_Q2, target_Q)
            """
            if(torch.isnan(critic_loss).any()):
                print('step 11 has nan')
                break
            """

            # Step 12: We backpropagate this Critic loss and update the parameters of the two Critic models with a SGD optimizer
            self.critic_optimizer.zero_grad()
            critic_loss.backward()
            torch.nn.utils.clip_grad_norm_(self.critic.parameters(), max_norm=10) 
            self.critic_optimizer.step()
            current_Q1, current_Q2 = self.critic(state, action)
            """
            if(torch.isnan(current_Q1).any() or torch.isnan(current_Q2).any()):
                for name, param in a.named_parameters():
                    if 'weight' in name:
                        print(name, param)
                for name, param in self.critic.named_parameters():
                    if 'weight' in name:
                        print(name, param)
                print('step 12 has nan')
                break
            """
            

            # Step 13: Once every two iterations, we update our Actor model by performing gradient ascent on the output of the first Critic model
            if it % policy_freq == 0:
                actor_loss = -self.critic.Q1(state, self.actor(state)).mean() # self.actor(state) -> action actor_loss -> score of the action
            
                """
                if(torch.isnan(actor_loss).any()):
                    print('actor_loss has nan')
                """
                self.actor_optimizer.zero_grad()
                actor_loss.backward()
                self.actor_optimizer.step()
                #print(self.actor(state))
                #print(self.critic(state, action))
                #for name, param in self.actor.named_parameters():
                #    if 'weight' in name:
                #        print(name, param)
                # Step 14: Still once every two iterations, we update the weights of the Actor target by polyak averaging
                for param, target_param in zip(self.actor.parameters(), self.actor_target.parameters()):
                    target_param.data.copy_(tau * param.data + (1 - tau) * target_param.data)

                # Step 15: Still once every two iterations, we update the weights of the Critic target by polyak averaging
                for param, target_param in zip(self.critic.parameters(), self.critic_target.parameters()):
                    target_param.data.copy_(tau * param.data + (1 - tau) * target_param.data)
        torch.cuda.empty_cache()
        

    # Making a save method to save a trained model
    def save(self, filename, directory):
        torch.save(self.actor.state_dict(), '%s/%s_actor.pth' % (directory, filename))
        torch.save(self.critic.state_dict(), '%s/%s_critic.pth' % (directory, filename))

    # Making a load method to load a pre-trained model
    def load(self, filename, directory):
        self.actor.load_state_dict(torch.load('%s/%s_actor.pth' % (directory, filename)))
        self.critic.load_state_dict(torch.load('%s/%s_critic.pth' % (directory, filename)))

class Actions_Scale(gym.ActionWrapper):
    def __init__(self, env, low_, high_):
        super().__init__(env)
        self.action_space = Box(low = low_, high= high_, shape=(1,), dtype=np.int)
    def action(self,act):
        return act



def evaluate_policy(env, policy, eval_episodes=1,seed=42):
    env.seed(seed)
    avg_reward = 0
    action_arr = []
    for _ in range(eval_episodes):
        obs = env.reset()
        done = False
        while not done:
            action = policy.evaluate_action(np.array(obs))
            #print(action)
            obs, reward, done, info = env.step(action)
            action_arr.append(info['action'][0])
            avg_reward += reward[0]
    
    action_ = action_arr[:-1]  # 最後一筆必賣出，所以拉掉
    avg_action = float(sum(action_)/len(action_))
    print("%2f, " %(avg_action))
    print("\n")
    print ("---------------------------------------")
    print ("Return of investment over the Evaluation Step: %f" % (reward))
    print ("---------------------------------------")
    return reward[0],action_arr,avg_action




