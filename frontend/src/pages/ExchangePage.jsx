import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { api } from "../api";

export default function ExchangePage() {
  const { id } = useParams();
  const [office, setOffice] = useState(null);
  const [rates, setRates] = useState([]);
  const [error, setError] = useState("");

  useEffect(() => {
    Promise.all([api.getOffice(id), api.getOfficeRates(id)])
      .then(([officeResponse, ratesResponse]) => {
        setOffice(officeResponse);
        setRates(ratesResponse);
      })
      .catch((e) => setError(e.message));
  }, [id]);

  return (
    <section>
      {error ? <p className="error">{error}</p> : null}
      {office ? (
        <>
          <h1>{office.name}</h1>
          <p>{office.city || "No city set"}</p>
          <p>{office.about || "No description available."}</p>
          <h2>Rates</h2>
          <div className="grid">
            {rates.map((rate) => (
              <article key={rate.id} className="card">
                <h3>
                  {rate.from_currency} to {rate.to_currency}
                </h3>
                <p>Buy: {rate.buy_rate}</p>
                <p>Sell: {rate.sell_rate}</p>
              </article>
            ))}
          </div>
        </>
      ) : (
        <p>Loading...</p>
      )}
    </section>
  );
}
