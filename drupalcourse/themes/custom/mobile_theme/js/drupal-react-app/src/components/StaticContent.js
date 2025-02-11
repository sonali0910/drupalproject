import React, { useEffect, useState } from 'react';
import vodacomLogo from './vodacom-logo.webp';

const StaticContent = () => {
  const [content, setContent] = useState('');

  const menuItems = [
    { id: 1, name: 'Vodacom', link: '/vodacom', image: vodacomLogo },
    { id: 2, name: 'Shop', link: '/shop' },
    { id: 3, name: 'Services', link: '/services' },
    { id: 4, name: 'VodaPay', link: '/vodapay' },
    { id: 5, name: 'Rewards', link: '/rewards' },
    { id: 6, name: 'Lifestyle', link: '/lifestyle' },
  ];

  return (
    <><div>
    </div><header className="header">
        <nav className="nav-menu">
          <ul className="menu-list">
            {menuItems.map((item) => (
              <li key={item.id} className="menu-item">
                <a href={item.link} className="menu-link">
                {item.image && <img src={item.image} alt={item.name} className="menu-image" />}
                {!item.image && item.name}
                </a>
              </li>
            ))}
          </ul>
        </nav>
      </header></>
  );
};

export default StaticContent;
