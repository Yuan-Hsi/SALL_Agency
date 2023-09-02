import DDPG_TD3_Infrustructure
import Enviorment_Setting
id = "0050"
env = Enviorment_Setting.ETFenv(id = id,reward_driver= 0.05, punish_driver = 0.05, length = 100, stock_num = 1000, interest_rate = 0.05)

env_name = "SALL_ENV" # Name of a environment (set it to any Continous environment you want)
seed = 42 # Random seed number 隨機種子
start_timesteps = 1e4 # Number of iterations/timesteps before which the model randomly chooses an action, and after which it starts to use the policy network 隨機探索步數
eval_freq = 5e3 # How often the evaluation step is performed (after how many timesteps) 多少步後做一次評估
max_timesteps = 30000 # Total number of iterations/timesteps 總共訓練步數
save_models = False # Boolean checker whether or not to save the pre-trained model 儲存模型
expl_noise = 0.1 # Exploration noise - STD value of exploration Gaussian noise 探索的動作噪訊 
batch_size = 100 # Size of the batch 訓練批次量
discount = 0.99 # Discount factor gamma, used in the calculation of the total discounted reward 報酬遞減因子
tau = 0.005 # Target network update rate 目標模型更新率
policy_noise = 0.2 # STD of Gaussian noise added to the actions for the exploration purposes policy網路動作噪訊
noise_clip = 0.5 # Maximum value of the Gaussian noise added to the actions (policy ) 動作噪訊最大值
policy_freq = 2 # Number of iterations to wait before the policy network (Actor model) is updated 多少 iteration 後，更新 policy 策略網路

# Selecting the device (CPU or GPU)
device = torch.device("cuda" if torch.cuda.is_available() else "cpu")
