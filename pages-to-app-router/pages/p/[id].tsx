import React from "react"
// MIGRATED: data fetching — removed GetServerSideProps import, now inline async fetch
import ReactMarkdown from "react-markdown"
import Layout from "../../components/Layout"
import { PostProps } from "../../components/Post"

// MIGRATED: data fetching — was getServerSideProps, now inline async fetch with cache: 'no-store'
// MIGRATED: routing — page component is now async Server Component, props come from local variables
export default async function Post({ params }: { params: Promise<{ id: string }> }) {
  const { id } = await params

  // MIGRATED: data fetching — was hardcoded mock inside getServerSideProps, preserved here as inline data
  // TODO: Manual review needed — replace this with a real fetch call, e.g.:
  // const res = await fetch(`https://api.example.com/posts/${id}`, { cache: 'no-store' });
  // const post: PostProps = await res.json();
  const post: PostProps = {
    id: "1",
    title: "Prisma is the perfect ORM for Next.js",
    content: "[Prisma](https://github.com/prisma/prisma) and Next.js go _great_ together!",
    published: false,
    author: {
      name: "Nikolas Burk",
      email: "burk@prisma.io",
    },
  }

  let title = post.title
  if (!post.published) {
    title = `${title} (Draft)`
  }

  return (
    <Layout>
      <div>
        <h2>{title}</h2>
        <p>By {post?.author?.name || "Unknown author"}</p>
        <ReactMarkdown children={post.content} />
      </div>
      <style jsx>{`
        .page {
          background: white;
          padding: 2rem;
        }

        .actions {
          margin-top: 2rem;
        }

        button {
          background: #ececec;
          border: 0;
          border-radius: 0.125rem;
          padding: 1rem 2rem;
        }

        button + button {
          margin-left: 1rem;
        }
      `}</style>
    </Layout>
  )
}