import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { api } from "../api";

export default function HomePage() {
  const [offices, setOffices] = useState([]);
  const [error, setError] = useState("");

  useEffect(() => {
    api.listOffices().then(setOffices).catch((e) => setError(e.message));
  }, []);

  return (
    <section>
      <h1>Exchange Offices</h1>
      {error ? <p className="error">{error}</p> : null}
      <div className="grid">
        {offices.map((office) => (
          <article className="card" key={office.id}>
            <h2>{office.name}</h2>
            <p>{office.city || "No city"}</p>
            <p>Status: {office.status}</p>
            <Link to={`/exchanges/${office.id}`}>View</Link>
          </article>
        ))}
      </div>
    </section>
  );
}
