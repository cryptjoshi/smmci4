import { SessionOptions } from "iron-session";

export interface SessionData {
  userId?: string;
  username?: string;
  img?: string;
  isLoggedIn: boolean;
  accessToken?:string;
  prefix?:string;
  role?:string;
}

export const defaultSession: SessionData = {
  isLoggedIn: false,
};

export const sessionOptions: SessionOptions = {
  // You need to create a secret key at least 32 characters long.
  password: "nV/TX3akP+0T2OOAhNVBk3AWzpjghsiF87CIaErrRU4=",
  cookieName: "lama-session",
  cookieOptions: {
    httpOnly: true,
    // Secure only works in `https` environments. So if the environment is `https`, it'll return true.
    secure: false,// process.env.NODE_ENV === "production",
  },
};

