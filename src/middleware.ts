import { NextRequest, NextResponse } from 'next/server';
import { cookies } from 'next/headers';
import { getSession } from 'app/actions/auth';


export async function middleware(req: NextRequest) {

   try {
    const path = req.nextUrl.pathname
    const session = await getSession()
     

    // ดึงข้อมูล token และ role จาก request headers หรือ cookies
   
    //console.log(cookies().get('token'))
     const token = await  session?.accessToken
     const role  = await session?.role
     const prefix = await session?.prefix
    
     const response = NextResponse.next();
     

     // ตั้งค่า cookie สำหรับ role
     //response.cookies.set('user-role', role);
    
    // ตรวจสอบว่ามีการส่ง token และ role มาหรือไม่
    if (!token || !role || !session.isLoggedIn) {
        return NextResponse.redirect(new URL('/auth/sign-in', req.url));
    }

    // // ตรวจสอบ role ของผู้ใช้
    // if (role !== 'sa' && req.nextUrl.pathname.startsWith('/admin')) {
    //     // ถ้าผู้ใช้ไม่ใช่ admin แต่พยายามเข้าถึงหน้า /admin, redirect ไปหน้า 403
    //     return NextResponse.redirect(new URL('/403', req.url));
    // }

    // if (role !== 'user' && req.nextUrl.pathname.startsWith('/dashboard')) {
    //     // ถ้าผู้ใช้ไม่มีสิทธิ์เข้าถึงหน้า /dashboard, redirect ไปหน้า 403
    //     return NextResponse.redirect(new URL('/403', req.url));
    // }
     // ตรวจสอบเส้นทางที่กำลังถูกเรียก
     const url = req.nextUrl;

    // กำหนดให้ role sa (Super Admin) สามารถเข้าถึงทุกเส้นทางได้
    // if (role === 'sa') {
    //     return NextResponse.next();
    // }

    // กำหนดการเข้าถึงเฉพาะเส้นทางที่ต้องการ
    if (url.pathname.startsWith('/admin') && role !== 'sa') {
        return NextResponse.redirect(new URL('/dashboard/firsttrans', req.url));
    } 
    // else {
    //     return NextResponse.redirect(new URL('/dashboard', req.url));
    // }
    
    return response;
}
catch(err){
    return NextResponse.redirect(new URL('/auth-signin', req.url));
}
}

// กำหนดเส้นทางที่ต้องการให้ Middleware ทำงาน
export const config = {
    matcher: ['/admin/:path*', '/dashboard/:path*'],
};
