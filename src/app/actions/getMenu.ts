// app/actions/getMenuItems.ts
 
import { revalidatePath } from "next/cache";
import { getSession } from "./auth";
// import { Role } from "../../types/user";
// import { MenuItem } from "../../types/menu";
import routes from "routes";
import { IRoute } from "types/navigation";


export interface MenuItem {
    name: string;
    path: string;
  }

 


export async function getMenuItems(): Promise<IRoute[]> {
  const session = await getSession()
 
  return  routes.filter(route => route.roles.includes(session.role))
}
