import Data_baseket
import importlib
import numpy as np
import traceback

def code_validatioin(account,agent,price_key,train_potion,times):
    try:
        # 取資料
        data_and_space =  Data_baseket.get_data(account,agent,price_key)
        data = data_and_space.data
        filters = data_and_space.filters
        og_data = data_and_space.og_data
        train_amount = int(data.shape[0]*train_potion/100)
        least_amount = int(data.shape[0]*(100-train_potion)/100/2) # 除 2 是為了分成驗證與測試集
        train_data = data[:train_amount]
        train_og = og_data[:train_amount]
        val_data = data[train_amount:train_amount+least_amount]
        val_og = og_data[train_amount:train_amount+least_amount]
        test_data = data[train_amount+least_amount:]
        test_og = og_data[train_amount+least_amount:]
        space_dict = data_and_space.space_dict

        if times > len(train_data) -9:
            return "ERR Your steps in a round is too short! "

        # 取環境
        name = "custom_env"+".test_"+account+"_"+agent+".ETFenv"
        module_name, class_name = name.rsplit('.', 1)
        module = importlib.import_module(module_name)
        desired_class = getattr(module, class_name)
        length = len(train_data) - 2
        env = desired_class(data = train_data, space_dict=space_dict, price_key = price_key, length = length,og_daata = train_og)

        obs = env.reset()
        done = False
        while not done:
            action = 2 * np.random.rand(1) -1
            n_obs, reward, done, info = env.step(action)
            if(n_obs.dtype == 'float64' and reward.dtype == 'float64' and type(done) == bool and info['action'].dtype == 'float64'):
                pass
            else:
                print('n_obs:',n_obs.dtype)
                print('reward:',reward.dtype)
                print('done:',type(done))
                print('action:',info['action'].dtype)
                return "ERR please check the type in state,reward,done,acion. Notice that you value using the self.reward[0] and self.action[0]"
        return "pass"
    except AttributeError as e:
        return  str(e)
    except Exception as e:
        # 捕捉所有類型的錯誤並印出錯誤類型和訊息
            
        return traceback.format_exc()