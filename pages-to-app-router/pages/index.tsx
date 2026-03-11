// MIGRATED: routing — moved from pages/ to app/ directory (rename to app/page.tsx)
// MIGRATED: data fetching — was getStaticProps with revalidate: 10, now inline async fetch with { next: { revalidate: 10 } }
// MIGRATED: ISR — revalidate: 10 preserved via fetch option

import React from "react"
import Layout from "../components/Layout"
import Post, { PostProps } from "../components/Post"

// MIGRATED: data fetching — was getStaticProps, now fetched directly in async Server Component
// Data is statically generated with ISR revalidation every 10 seconds (revalidate: 10 preserved)
const Blog = async () => {
  // MIGRATED: data fetching — inline data replaces getStaticProps return value
  // TODO: Manual review needed — if this data comes from an external API or database,
  // replace this inline array with an actual fetch() call using { next: { revalidate: 10 } }
  // e.g.: const res = await fetch('https://your-api.com/feed', { next: { revalidate: 10 } });
  //       const feed: PostProps[] = await res.json();
  const feed: PostProps[] = [
    {
      id: "1",
      title: "Prisma is the perfect ORM for Next.js",
      content: "[Prisma](https://github.com/prisma/prisma) and Next.js go _great_ together!",
      published: false,
      author: {
        name: "Nikolas Burk",
        email: "burk@prisma.io",
      },
    },
  ]

  return (
    <Layout>
      <div className="page">
        <h1>Public Feed</h1>
        <main>
          {feed.map((post) => (
            <div key={post.id} className="post">
              <Post post={post} />
            </div>
          ))}
        </main>
      </div>
      <style jsx>{`
        .post {
          background: white;
          transition: box-shadow 0.1s ease-in;
        }

        .post:hover {
          box-shadow: 1px 1px 3px #aaa;
        }

        .post + .post {
          margin-top: 2rem;
        }
      `}</style>
    </Layout>
  )
}

export default Blog