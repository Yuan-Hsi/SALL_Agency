import torch
import numpy as np
import torch.nn as nn
import torch.nn.functional as F
import pandas as pd
import joblib

def validate(account,agent,para_dic):
    info = pd.read_csv('../File_Repository/agent_info/'+account + "_" + agent+".csv").iloc[0]
    feature_start = list(info.index).index('開盤價(元)')
    feature_end = list(info.index).index('股利殖利率-TSE')
    features = ['MACD','最大可購買股數','持有股數']

    for i in range(feature_start,feature_end+1):
        if(info[i] == 1):
            features.append(list(info.index)[i])

    for feature in features:
        if(feature in para_dic):
            if(type(para_dic[feature]) != np.ndarray or para_dic[feature].dtype != 'float64'):
                return 'out'
            else:
                para_dic[feature] = np.expand_dims(para_dic[feature],axis=0)
        else:
            return 'out'
    return para_dic

def inference(account,agent,para_dic):

    model_directory = '../Model_Repository/pytorch_models'
    filename = "TD3_" + account + "_" + agent
    file_directory = '../File_Repository/agent_info/'+account + "_" + agent+".csv"
    scale_directory = '../File_Repository/scalers/'+account + "_" + agent

    para_dic = validate(account,agent,para_dic)
    if (para_dic == 'out'):
        print('請確認輸入資料格式!')
        return -10

    para_list = list(para_dic.values())
    input_arr = np.concatenate(para_list, axis=0)
    unfilter_feature = input_arr[:-2].T
    buy_max = input_arr[-2]
    sell_max = input_arr[-1]

    # 資料前處理
    agent_info = pd.read_csv(file_directory).iloc[0]
    if agent_info['amount_scaler'] != -1:

        if agent_info['Standardise'] == 1:
            filtor = joblib.load(scale_directory+'/StandardScaler.save')
            data = filtor.transform(unfilter_feature)
        if agent_info['Normalize'] == 1:
            filtor = joblib.load(scale_directory+'/Normalize.save')
            data = filtor.transform(unfilter_feature)
        if agent_info['Scaleing'] == 1:
            filtor = joblib.load(scale_directory+'/Scaleing.save')
            data = filtor.transform(unfilter_feature)
            
        buy_max = buy_max / agent_info['amount_scaler']
        sell_max = sell_max / agent_info['amount_scaler']
        
    else:
        data = unfilter_feature

    # 將 buy_max 與 sell_max 放入
    data = np.concatenate((data, np.array([buy_max]).T), axis=1)
    data = np.concatenate((data, np.array([sell_max]).T), axis=1)
    data[data == 0] = 1e-100

    # 資料處理完了，建模型
    class Actor(nn.Module):
    
        def __init__(self, state_dim, action_dim, max_action):
            super(Actor, self).__init__()
            self.hidden_size = 400
            
            self.lstm = nn.LSTM(input_size=state_dim[1], hidden_size=self.hidden_size, num_layers=1, batch_first=True)
            #self.layer_1 = nn.Linear(200*state_dim[0], 400)
            self.layer_test = nn.Linear(self.hidden_size, 300)
            #self.layer_2 = nn.Linear(400, 150)
            #self.ln2 = nn.LayerNorm(300)
            self.layer_3 = nn.Linear(300, action_dim)
            self.max_action = max_action
            
            torch.nn.init.xavier_normal_(self.layer_test.weight) 
            torch.nn.init.xavier_normal_(self.layer_3.weight) 
        
        def evaluate(self,x):
            x = x.unsqueeze(0)
            x, _ = self.lstm(x)
            x = x[:, -1, :]
            x = F.relu(self.layer_test(x))
            x = nn.Tanh()(self.layer_3(x))
            return x 

    actor = Actor(state_dim=data.shape, action_dim=1, max_action=1)
    actor.load_state_dict(torch.load('%s/%s_actor.pth' % (model_directory, filename)))
    state = torch.Tensor(data)
    action = actor.evaluate(state).cpu().data.numpy().flatten()
                
    return action[0]

