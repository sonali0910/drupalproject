import React, { useEffect, useState } from 'react';

const MobileShowcaseComponent = () => {
  const [mobileData, setMobileData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch('http://localhost/drupalcourse/api/getMobileData')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        setMobileData(data);
        setLoading(false);
      })
      .catch(error => {
        setError(error);
        setLoading(false);
      });
  }, []);

  if (loading) {
    return <div>Loading...</div>;
  }

  if (error) {
    return <div>Error: {error.message}</div>;
  }

  return (
    <div className="mobile-showcase">
      {mobileData.map((item, index) => (
        <div key={index} className="mobile-item">

          {item.image && <img src={item.image} alt={item.title}  class="left-image" />}
          <div class="right-content">
            <h3>{item.title}</h3>
            <h1>{item.subtitle}</h1>
            <p>{item.body}</p>
         
          {item.download_link && (
            <a class="mobile-link" href={item.download_link.url} target="_blank" rel="noopener noreferrer">
              {item.download_link.text}
            </a>
          )}
          </div>
        </div>
      ))}
    </div>
  );
};

export default MobileShowcaseComponent;
