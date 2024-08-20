//import "../app/globals.css";
import Link from "next/link";

const Switcher = () => {
  return (
    <div className="space-x-3">
      <Link
        href="/en"
        className="bg-blue-500 text-white font-semibold rounded-md p-3"
      >
        EN
      </Link>
      <Link
        href="/fr"
        className="bg-blue-500 text-white font-semibold rounded-md p-3"
      >
        FR
      </Link>
      <Link
        href="/nl"
        className="bg-blue-500 text-white font-semibold rounded-md p-3"
      >
        NL
      </Link>
    </div>
  );
};

export default Switcher;