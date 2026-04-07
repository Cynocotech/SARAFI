import { Link, Route, Routes } from "react-router-dom";
import HomePage from "./pages/HomePage";
import ExchangePage from "./pages/ExchangePage";

export default function App() {
  return (
    <div className="app">
      <header className="topbar">
        <Link to="/" className="brand">
          Exchange Landing
        </Link>
      </header>

      <main className="container">
        <Routes>
          <Route path="/" element={<HomePage />} />
          <Route path="/exchanges/:id" element={<ExchangePage />} />
        </Routes>
      </main>
    </div>
  );
}
