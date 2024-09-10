 //@ts-nocheck
// import { getSession } from "app/actions/auth";
// import { sessionOptions } from "lib";
// //import { sessionOptions } from "../../../lib/session";
// import { NextApiRequest, NextApiResponse } from "next";

// // async function sessionHandler(req: NextApiRequest, res: NextApiResponse) {
// //   const session = await getSession()
  
// //   res.json({ user: session.role });
// // }

// // export const GET = withIronSessionApiRoute(sessionHandler, sessionOptions);

// export function mergeHeaders(...headersList: (HeadersInit | undefined)[]) {
//   const mergedHeaders = new Headers()
//   for (const headers of headersList) {
//     const headersObj = headers instanceof Headers ? headers : new Headers(headers)
//     for (const [key, value] of headersObj.entries()) {
//       mergedHeaders.append(key, value)
//     }
//   }
//   return mergedHeaders
// }
// export function createResponse(originalResponse: Response, bodyString: string, options?: ResponseInit) {
//   return new Response(bodyString, {
//     status: options?.status ?? originalResponse.status,
//     statusText: options?.statusText ?? originalResponse.statusText,
//     headers: mergeHeaders(options?.headers, originalResponse.headers)
//   })
// }

// export async function GET(req: Request,res:Response) {
//   const session = await getSession()
  
//   return createResponse(res, JSON.stringify({ user: session }), {
//     headers: { 'Content-Type': 'application/json' }
//   })
// }

// import type { NextRequest, NextResponse } from 'next'
// import { getSession } from "app/actions/auth";
// type ResponseData = {
//   message: string
// }
 
//  async function handler(
//   req: NextRequest,
//   res: NextResponse
// ) {
//   const session = await getSession()

//   res.status(200).json({ user: session })
// }

// export { handler as GET, handler as POST };


"use server";

import { NextRequest, NextResponse } from "next/server";
import { getSession } from "app/actions/auth";
async function GET(req: NextRequest) {
  const session = await getSession()
  return NextResponse.json(
    { user: session},
    {
      status: 200,
    }
  );
}

export { GET };
//  async function handler(
//   req: NextApiRequest,
//   res: NextApiResponse
// ) {
//   try {
//     const session = await getSession()
//     res.status(200).json({ user:session })
//   } catch (err) {
//     res.status(500).json({ error: 'failed to load data' })
//   }
// }
// export { handler as GET, handler as POST };