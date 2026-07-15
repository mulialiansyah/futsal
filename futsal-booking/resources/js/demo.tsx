// resources/js/demo.tsx
import * as React from "react";
import { RideBookingForm } from "@/components/ui/ride-booking-form";

const Demo = () => {
  const handleSearch = (details: {
    lokasi: string;
    lapangan: string;
    tanggal: string;
    waktu: string;
  }) => {
    console.log("Mencari lapangan futsal dengan detail:", details);
    alert(`Mencari ketersediaan lapangan di ${details.lokasi || "semua lokasi"} untuk ${details.lapangan || "semua lapangan"}\nPada ${details.tanggal} jam ${details.waktu}`);
  };

  return (
    <div className="flex items-center justify-center w-full min-h-screen bg-muted py-12">
      <RideBookingForm
        imageUrl="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1470&auto=format&fit=crop"
        city="Jakarta, ID"
        onSearch={handleSearch}
        className="my-8"
      />
    </div>
  );
};

export default Demo;
