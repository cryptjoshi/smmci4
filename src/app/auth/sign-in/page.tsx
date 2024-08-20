 'use server'

 
import { getSession } from "app/actions/auth";
import LoginForm from "components/auth/LoginForm";

import { redirect } from "next/navigation";

const LoginPage = async () => {
  const session = await getSession()

  if (session.isLoggedIn) {
    redirect("/");
  }

  return (
    <>
      <LoginForm />
    </>
  );
};

export default LoginPage
 
