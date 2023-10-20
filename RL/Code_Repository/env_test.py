from DDPG_TD3_Infrustructure import TD3,ReplayBuffer ,evaluate_policy
import Data_baseket
import importlib

def code_validatioin(account,agent,price_key):
    try:
        # 取資料
        test_amount = 300
        data_and_space =  Data_baseket.get_data(account,agent,price_key)
        data = data_and_space.data
        train_data = data[:-test_amount]
        val_data = data[-test_amount:]
        space_dict = data_and_space.space_dict

        # 取環境
        name = "custom_env"+"."+account+"_"+agent+".ETFenv"
        module_name, class_name = name.rsplit('.', 1)
        module = importlib.import_module(module_name)
        desired_class = getattr(module, class_name)
        env = desired_class(data = train_data, space_dict=space_dict, price_key = price_key, reward_driver= 0.05, punish_driver = 0.05, length = 100, stock_num = 1000, interest_rate = 0.05)

        episodes = 10
        for episodes in range(1, episodes+1):
            state = env.reset()
            done = False
            score = 0
            
            while not done:
                env.render()
                action =env.action_space.sample()
                n_state, reward, done, info = env.step(action)
                score += reward
        return "pass"

    except Exception as e:
        # 捕捉所有類型的錯誤並印出錯誤類型和訊息
        return "An error occurred:", type(e).__name__, str(e)