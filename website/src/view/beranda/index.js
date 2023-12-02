import IndexCard from "../../components/card_index";
import { Swiper, SwiperSlide } from "swiper/react";
import { Autoplay } from "swiper/modules";
import video from "../../assets/video/yid.mp4";
function IndexBeranda() {
  return (
    <div className="w-full">
      <div className="h-auto md:w-4/6 mx-auto md:py-20">
        <div className="font-black md:text-7xl md:w-11/12 md:mt-20 text-slate-600 h-[500px] ">
          <span className=" text-color-primary">Lihat Review&nbsp;</span>
          <span>moment yang diabadikan pada setiap</span>
          <span className=" text-color-primary">&nbsp;Makanan</span>
          <span>&nbsp;dan&nbsp;</span>
          <span className=" text-color-primary">Tempat Hiburan</span>
        </div>
        <div className=" grid grid-cols-1 md:grid-cols-3 gap-7 mt-12">
          <IndexCard video={video} />
          <IndexCard video={video} />
          <IndexCard video={video} />
        </div>
        <div className="md:mt-20 mx-auto flex justify-center">
          <div className="hover:-translate-y-1 hover:duration-300 rounded-full text-center font-bold shadow-xl cursor-pointer px-6 pt-2 pb-3 bg-color-primary text-white text-sm">
            <span>Mulai Explore</span>
          </div>
        </div>
        <div className="md:mt-32">
          <h4 className="font-bold text-xl text-slate-600">
            Bantu UMKM Tumbuh
          </h4>
          <p className="text-xs">
            Kami membantu UMKM meningkatkan kualitas produk dan bertumbuh
          </p>
          <div className="md:mt-5">
            <div className=" h-72 w-full">
              <Swiper
                spaceBetween={0}
                slidesPerView={1}
                loop={true}
                modules={[Autoplay]}
                autoplay={{
                  delay: 2500,
                  disableOnInteraction: false,
                }}
              >
                <SwiperSlide>
                  <div className="h-72 w-full bg-blue-300"></div>
                </SwiperSlide>
                <SwiperSlide>
                  <div className="h-72 w-full bg-red-300"></div>
                </SwiperSlide>
                <SwiperSlide>
                  <div className="h-72 w-full bg-green-300"></div>
                </SwiperSlide>
                <SwiperSlide>
                  <div className="h-72 w-full bg-yellow-300"></div>
                </SwiperSlide>
              </Swiper>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default IndexBeranda;
