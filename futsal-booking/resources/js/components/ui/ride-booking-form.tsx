// resources/js/components/ui/ride-booking-form.tsx
"use client";

import * as React from "react";
import { motion } from "framer-motion";
import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

// Provide a simple cn utility since it might not exist yet
function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

import { MapPin, Search, Calendar, Clock, ArrowRight, LayoutGrid } from "lucide-react";

interface FutsalBookingFormProps extends React.HTMLAttributes<HTMLDivElement> {
  imageUrl: string;
  city?: string;
  onSearch: (details: {
    lokasi: string;
    lapangan: string;
    tanggal: string;
    waktu: string;
  }) => void;
}

export const RideBookingForm = React.forwardRef<HTMLDivElement, FutsalBookingFormProps>(
  ({ className, imageUrl, city = "Jakarta, ID", onSearch, ...props }, ref) => {
    const [lokasi, setLokasi] = React.useState("");
    const [lapangan, setLapangan] = React.useState("");
    const [tanggal] = React.useState("Hari Ini");
    const [waktu] = React.useState("Sekarang");

    const handleSubmit = (e: React.FormEvent) => {
      e.preventDefault();
      onSearch({ lokasi, lapangan, tanggal, waktu });
    };
    
    const containerVariants = {
      hidden: { opacity: 0 },
      visible: {
        opacity: 1,
        transition: { staggerChildren: 0.1, delayChildren: 0.2 },
      },
    };

    const itemVariants = {
      hidden: { y: 20, opacity: 0 },
      visible: {
        y: 0,
        opacity: 1,
        transition: { type: "spring", stiffness: 100 },
      },
    };

    return (
      <div
        className={cn("w-full max-w-6xl mx-auto p-4 lg:p-8", className)}
        ref={ref}
        {...props}
      >
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center bg-background rounded-lg overflow-hidden shadow-lg border border-border/50">
          {/* Left Side: Booking Form */}
          <motion.div 
            className="p-4 sm:p-8"
            variants={containerVariants}
            initial="hidden"
            animate="visible"
          >
            <motion.div variants={itemVariants} className="mb-6">
              <span className="text-sm text-muted-foreground flex items-center">
                <MapPin className="h-4 w-4 mr-2 text-primary" />
                {city}
                <a href="#" className="ml-2 text-sm font-medium text-primary hover:underline">
                  Ubah kota
                </a>
              </span>
            </motion.div>

            <motion.h1 variants={itemVariants} className="text-4xl sm:text-5xl font-bold text-foreground mb-8 leading-tight">
              Booking Lapangan Futsal Mudah
            </motion.h1>

            <form onSubmit={handleSubmit} className="space-y-4">
              {/* Location Inputs with connecting line */}
              <motion.div variants={itemVariants} className="relative bg-muted/40 p-4 rounded-xl border border-border/50">
                <div className="absolute left-6 top-9 bottom-9 w-px bg-border border-l border-dashed"></div>
                
                <div className="relative flex items-center mb-2">
                  <div className="z-10 bg-background p-1.5 rounded-full border shadow-sm">
                     <MapPin className="h-4 w-4 text-primary" />
                  </div>
                  <input
                    type="text"
                    placeholder="Lokasi Futsal / Kota"
                    value={lokasi}
                    onChange={(e) => setLokasi(e.target.value)}
                    className="w-full pl-4 pr-10 py-2 bg-transparent text-foreground placeholder:text-muted-foreground focus:outline-none"
                    aria-label="Lokasi Futsal / Kota"
                  />
                </div>
                
                <hr className="border-border mx-12 my-2" />

                <div className="relative flex items-center mt-2">
                   <div className="z-10 bg-background p-1.5 rounded-full border shadow-sm">
                     <LayoutGrid className="h-4 w-4 text-primary" />
                   </div>
                  <input
                    type="text"
                    placeholder="Nama Lapangan (Opsional)"
                    value={lapangan}
                    onChange={(e) => setLapangan(e.target.value)}
                    className="w-full pl-4 py-2 bg-transparent text-foreground placeholder:text-muted-foreground focus:outline-none"
                    aria-label="Nama Lapangan"
                  />
                </div>
              </motion.div>

              {/* Date and Time Inputs */}
              <motion.div variants={itemVariants} className="grid grid-cols-2 gap-4">
                <div className="flex items-center bg-muted/40 rounded-xl px-4 py-3.5 border border-border/50 cursor-pointer hover:bg-muted/60 transition-colors">
                  <Calendar className="h-5 w-5 text-primary" />
                  <span className="ml-3 text-foreground font-medium">{tanggal}</span>
                </div>
                <div className="flex items-center bg-muted/40 rounded-xl px-4 py-3.5 border border-border/50 cursor-pointer hover:bg-muted/60 transition-colors relative">
                  <Clock className="h-5 w-5 text-primary" />
                  <span className="ml-3 text-foreground font-medium">{waktu}</span>
                  <select aria-label="Select time" className="absolute opacity-0 inset-0 w-full h-full cursor-pointer"></select>
                </div>
              </motion.div>

              {/* Action Buttons */}
              <motion.div variants={itemVariants} className="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6 pt-6">
                <button
                  type="submit"
                  className="inline-flex items-center justify-center whitespace-nowrap rounded-xl text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 hover:scale-[1.02] active:scale-[0.98] h-12 px-8 shadow-md"
                >
                  <Search className="w-4 h-4 mr-2" />
                  Cek Ketersediaan
                </button>
                <a
                  href="#"
                  className="text-sm font-medium text-muted-foreground hover:text-primary transition-colors group flex items-center"
                >
                  Masuk untuk melihat riwayat
                  <ArrowRight className="inline-block h-4 w-4 ml-1 transform transition-transform group-hover:translate-x-1" />
                </a>
              </motion.div>
            </form>
          </motion.div>

          {/* Right Side: Image */}
          <motion.div 
            className="hidden lg:block w-full h-full p-4 lg:p-6"
            initial={{ opacity: 0, scale: 0.95 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.5, ease: "easeOut" }}
          >
            <div className="relative w-full h-full min-h-[500px] rounded-2xl overflow-hidden shadow-2xl">
              <img
                src={imageUrl}
                alt="Ilustrasi lapangan futsal"
                className="absolute inset-0 w-full h-full object-cover transition-transform duration-700 hover:scale-105"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            </div>
          </motion.div>
        </div>
      </div>
    );
  }
);

RideBookingForm.displayName = "RideBookingForm";
