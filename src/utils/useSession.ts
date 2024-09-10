import { SessionData } from "lib";
import { useEffect, useState } from "react";

export type Role = "admin" | "user" | "sa";
export interface User {
    user:any
    username: string;
    role: Role;
  }

export function useSession() {
  const [user, setUser] = useState<User>(null);

  useEffect(() => {
    fetch("/api/session")
      .then((res) => res.json())
      .then((data) => setUser(data || null));
  }, []);

  return {user}
}