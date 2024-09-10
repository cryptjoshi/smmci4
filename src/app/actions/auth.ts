"use server"


import { getIronSession } from "iron-session";
import { SessionData, defaultSession, sessionOptions } from "lib";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";


// ADD THE GETSESSION ACTION
export async function getSession() {
  const session = await getIronSession<SessionData>(cookies(), sessionOptions);

  // If user visits for the first time session returns an empty object.
  // Let's add the isLoggedIn property to this object and its value will be the default value which is false
  if (!session.isLoggedIn) {
    session.isLoggedIn = defaultSession.isLoggedIn;
  }

  return session;
}
export async function logout() {
  const session = await getSession();
  session.destroy();
  redirect("/")
}

// ADD THE LOGIN ACTION
export async function login(
  formUsername:string,formPassword:string
  //formData: FormData
) {
  const session = await getSession()
 
  const raw = JSON.stringify({
    "username": formUsername,
    "password": formPassword
});
 
 
  const response = await fetch('https://wallet.tsxbet.net/api/Admin/Login', { method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  },
  body: raw
});
 
  if (!response.ok) {
    //throw new Error('Failed to fetch data');
    return {status:false}
  }
  const result = await response.json()
  // IF CREDENTIALS ARE WRONG RETURN AN ERROR
  // if(!user){
  //   return { error: "Wrong Credentials!" }
  // }

  // You can pass any information you want
  session.isLoggedIn = result.status;
  session.accessToken = result.data.accessToken;
  session.username = formUsername;
  session.prefix = result.data.prefix
  session.role = result.data.role

  await session.save();
  
  return {status:result.status}
  // redirect("/")
 
}


export async function depositAmount(data:any){
  const session = await getSession()
  // let raw = {
  //   "id": data.id,
  //   "username":data.username,
  //   "amount": data.amount,
    
  // }

  let url = "https://wallet.tsxbet.net/api/Admin/Deposit"
  url = "https://wallet.tsxbet.net/1stpay/webhook"

  let raw = {
 
      "transactionID": "01fe05b2-f097-4269-82a3-cf4056842xxx5",
      "merchantID": "flukxzy",
      "type": "payin",
      "amount": data.amount,
      "fee": 0.09,
      "transferAmount": data.amount,
      "bankAccountNo": "0511609542",
      "bankAccountName": "พงศธร สนิทไทย",
      "bankCode": "KBANK",
      "verify": 1,
      "createAt": "2024-08-27 16:57:01",
      "expiredAt": "2024-08-27 17:07:01",
      "isExpired": 1,
      "ref": data.username,
      "provider": "1stpay"
    } 
  
  if(!session.isLoggedIn)
  throw new Error('Failed to fetch data');

  const response = await fetch(url, { method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  session.accessToken
  },
  body: JSON.stringify(raw)
});
 
  if (!response.ok) {
    return false
  }
  const result = await response.json()
   
  return {result}
}

export async function withdrawAmount(data:any){
  const session = await getSession()
  // let raw = {
  //   "id": data.id,
  //   "username":data.username,
  //   "amount": data.amount
  // }

  let url = "https://wallet.tsxbet.net/api/Admin/Deposit"
  url = "https://wallet.tsxbet.net/1stpay/webhook"

  let raw = {
 
      "transactionID": "01fe05b2-f097-4269-82a3-cf4056842yyy3",
      "merchantID": "flukxzy",
      "type": "payout",
      "amount": data.amount,
      "fee": 0.09,
      "transferAmount": data.amount,
      "bankAccountNo": "0511609542",
      "bankAccountName": "พงศธร สนิทไทย",
      "bankCode": "KBANK",
      "verify": 1,
      "createAt": "2024-08-27 16:57:01",
      "expiredAt": "2024-08-27 17:07:01",
      "isExpired": 1,
      "ref": data.username,
      "provider": "1stpay"
    } 

  if(!session.isLoggedIn)
  throw new Error('Failed to fetch data');

  const response = await fetch(url, { method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  session.accessToken
  },
  body: JSON.stringify(raw)
});
 
  if (!response.ok) {
    return false
  }
  const result = await response.json()
   
  return {result}
}

export async function updateUsers(data:any){

 
  const session = await getSession()
  let raw =  {
    "id": data.id,
    "username": data.username
 
    //"password": data.newpassword,
    //"role":data.role,
    //"status":data.status,
    //"prefix":data.prefix
}
if(data.newpassword)
  Object.assign(raw,{"newpassword":data.newpassword})



if(!session.isLoggedIn)
 throw new Error('Failed to fetch data');
 
  const response = await fetch('https://wallet.tsxbet.net/api/Admin/User', { method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  session.accessToken
  },
  body: JSON.stringify(raw)
});
 
  if (!response.ok) {
    return false
  }
  const result = await response.json()
   
  return {result}

}

export async function updateAdmin(data:any){

 

  
  let raw =  {
    "username": data.username,
    //"password": data.newpassword,
    "role":data.role,
    "status":data.status,
    "prefix":data.prefix
}
if(data.newpassword)
  Object.assign(raw,{"newpassword":data.newpassword})

const session = await getSession()

if(!session.isLoggedIn)
 throw new Error('Failed to fetch data');
//console.log(raw)
  const response = await fetch('https://wallet.tsxbet.net/api/Admin/Update', { method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  session.accessToken
  },
  body: JSON.stringify(raw)
});
 
  if (!response.ok) {
    return false
  }
  const result = await response.json()
   
  return {result}

}

export async function insertAdmin(data:any){


  const raw = JSON.stringify({
    "username": data.username,
    "password": data.password,
    "role":data.role,
    "prefix":data.prefix
});
 
const session = await getSession()

if(!session.isLoggedIn)
 throw new Error('Failed to fetch data');

  const response = await fetch('https://wallet.tsxbet.net/api/Admin/Register', { method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  session.accessToken
  },
  body: raw
});
 
  if (!response.ok) {
    return false
  }
  const result = await response.json()
   
  return {result}

}

export async function getData(startdate:string,stopdate:string) {
  const token = "";//localStorage.getItem('token');
  const session = await getSession() 
  const res = await fetch('https://report.tsxbet.net/reports/sumwinloss', { cache: 'no-store' ,
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + token
    },
    body: JSON.stringify({ "startdate": startdate, "stopdate": stopdate, "prefix": session.prefix, "statement_type": "all", "status": "all" })

  });
  
  return res.json();
}

export async function getTransaction (startdate:any,stopdate:any)  {
  const token =  "" ;//getToken() //localStorage.getItem('token');
  const session = await getSession() 
  const raw = JSON.stringify({"startdate":startdate,"stopdate":stopdate,"prefix":session.prefix,"statement_type":"all","status":"all"});
  const res = await fetch('https://report.tsxbet.net/reports/all/statement', { method: 'POST',
    headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  token
    },
  body: raw
  });
  return res.json();
 
 
}

export async function updateFirstTransaction ()  {
  const token =  "" ;//getToken() //localStorage.getItem('token');
  const session = await getSession() 
  const raw = JSON.stringify({"prefix":session.prefix,"statement_type":"all","status":"all"});
  const res = await fetch('https://report.tsxbet.net/reports/first/update', { method: 'POST',
    headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  token
    },
  body: raw
  });
  return res.json();
 
 
}

export async function getPrefixs() {
  const session = await getSession() 
  const raw = JSON.stringify({"prefix":session.prefix,"statement_type":"all","status":"all"});
  const res = await fetch('https://wallet.tsxbet.net/api/Admin/Prefixs', { method: 'POST',
  headers: {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
//  'Authorization': 'Bearer ' +  token
  },
body: raw
});
return res.json();
}

export async function getFirstTransaction (startdate:any,stopdate:any)  {
  const token =  "" ;//getToken() //localStorage.getItem('token');
  const session = await getSession() 
  const raw = JSON.stringify({"startdate":startdate,"stopdate":stopdate,"prefix":session.prefix,"statement_type":"all","status":"all"});
  const res = await fetch('https://report.tsxbet.net/reports/first/statement', { method: 'POST',
    headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  token
    },
  body: raw
  });
  return res.json();
 
 
}

export const compareDates = (date1:any, date2:any) =>{
  const d1 = new Date(date1);
  const d2 = new Date(date2);

  // เปรียบเทียบวันที่ ถ้า date2 น้อยกว่า date1 ให้แสดง false
  if (d2 < d1) {
    return false;
  }
  return true;
}

export const getTransactions = async (id:string,startdate:string,enddate:string)=>{
  const token =  "" ;//getToken() //localStorage.getItem('token');
  const session = await getSession() 
  const raw = JSON.stringify({"startdate":startdate,"stopdate":enddate,"prefix":session.prefix,"statement_type":"all","status":"all"});
  const res = await fetch(`https://report.tsxbet.net/reports/winloss/${id}`,{method:'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  token
    },
  body: raw
  });
  return res.json();
}

  

export async function getUserstatus(prefix:string){
  const token =  "" ;//getToken() //localStorage.getItem('token');
  const session = await getSession() 
  const raw = JSON.stringify({"prefix":prefix,"statement_type":"all","status":"all"});

  const  res = await fetch('https://report.tsxbet.net/reports/count/userstatus', { method: 'POST',
    headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' +  token
    },
  body: raw
  });
  return  res.json();  
}
 
