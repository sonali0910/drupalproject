import React, { useEffect, useState } from 'react';
import { Carousel } from 'react-responsive-carousel';
import 'react-responsive-carousel/lib/styles/carousel.min.css';

const CarouselComponent = () => {
  const [carouselItems, setCarouselItems] = useState([]);

  useEffect(() => {
    fetch('http://localhost/drupalcourse/api/getCarouselData')
      .then(response => response.json())
      .then(data => setCarouselItems(data))
      .catch(error => console.error('Error fetching carousel data:', error));
  }, []);

  const handleButtonClick = (url) => {
    window.open(url, '_blank');
  };

  return (
    <Carousel>
      {carouselItems.map((item, index) => (
        <div key={index}
        style={{
          backgroundImage: `url(${item.bgimage})`,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
          height: '650px',
        }}
        >
          
          <div className="carousel-caption" class="carousel-caption">
            <div class="carousel_title">{item.title}</div>
            <p class="carousel_subtitle">{item.subtitle}</p>
            <p class="mobile_model">{item.mobilemodel} <span class="pm">PMx36</span></p>
            {item.button1 && item.button1.text && item.button1.url && (
              <button class="button1" onClick={() => handleButtonClick(item.button1.url)}>
              {item.button1.text}
            </button>
            )}
            {item.button2 && item.button2.text && item.button2.url && (
              <button class="button2" onClick={() => handleButtonClick(item.button2.url)}>
              {item.button2.text}
            </button>
            )}
          </div>
        </div>
      ))}
    </Carousel>
  );
};

export default CarouselComponent;
