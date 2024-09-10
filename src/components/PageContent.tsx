'use client';
import { FC, ReactNode } from 'react';

interface PageContentProps {
  title: string;
  children: ReactNode;
}

const PageContent: FC<PageContentProps> = ({ title, children }) => {
  return (
    <div className="page-content">
      <h1>{title}</h1>
      <div>{children}</div>
      <style jsx>{`
        .page-content {
          padding: 2rem;
          margin-top: 3vh;
        }
        h1 {
          font-size: 2rem;
          margin-left:2rem;
        }
      `}</style>
    </div>
  );
};

export default PageContent;