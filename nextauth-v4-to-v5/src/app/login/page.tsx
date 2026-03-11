import { auth } from '@/auth' // MIGRATED: NextAuth v5 — replaced getServerSession with auth()
import React from 'react'
import { redirect } from 'next/navigation';
import RegisterForm from './Form';
import LoginForm from './Form';

const page = async () => {
  const session = await auth(); // MIGRATED: NextAuth v5 — replaced getServerSession(authOptions) with auth()

  if (session) {
    redirect("/");
  }


  return (
    <section className='container h-screen flex items-center justify-center'>
        <div className='w-[800px]'>
            <LoginForm/>
        </div>
    </section>
  )
}

export default page