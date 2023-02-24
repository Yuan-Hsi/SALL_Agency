import pandas as pd
from fastapi import FastAPI
from fastapi import Body
from fastapi.responses import JSONResponse
import uvicorn
from pydantic import BaseModel
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI() # 建立一個 Fast API application

origins = ["*"]

app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class Input_data1_ds(BaseModel):
    state_1 : float
    state_2 : float
    state_3 : float
    state_4 : float
    state_5 : float
    state_6 : float
    state_7 : float
    state_8 : float


@app.post("/data_preprocessing")
def data_preprocessing(input_data: Input_data1_ds = Body(...)):

    input_data = input_data.dict()

    input_df =  pd.DataFrame(input_data,index=[0])
    
    output_data = {'state_1' : input_df.iloc[0,0],
                   'state_2' : input_df.iloc[0,1]
                   }

    return JSONResponse(output_data)


@app.post("/hello")
def test():
    return "Hello World"


if __name__ == "__main__":
    uvicorn.run(app = 'test:app', host="0.0.0.0", port=6001, reload=True) #app = python檔名！

