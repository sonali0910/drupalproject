import React from 'react';
import CarouselComponent from './components/CarouselComponent';
import StaticContent from './components/StaticContent';
import MobileShowcaseComponent from './components/MobileShowcaseComponent';
import './App.css';

function App() {
  return (
    <div className="App">
      <main>
        <StaticContent />
        <CarouselComponent />
        <MobileShowcaseComponent />
        
      </main>
    </div>
  );
}

export default App;