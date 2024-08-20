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
  let raw = {
    "id": data.id,
    "username":data.username,
    "amount": data.amount,
    
  }
  if(!session.isLoggedIn)
  throw new Error('Failed to fetch data');

  const response = await fetch('https://wallet.tsxbet.net/api/Admin/Deposit', { method: 'POST',
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
  let raw = {
    "id": data.id,
    "username":data.username,
    "amount": data.amount
  }
  if(!session.isLoggedIn)
  throw new Error('Failed to fetch data');

  const response = await fetch('https://wallet.tsxbet.net/api/Admin/withdraw', { method: 'POST',
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
console.log(raw)
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