function IndexCard({ video }) {
  return (
    <div>
      <div className="h-auto w-full">
        <div className="h-[500px] relative">
          <video
            src={video}
            loop
            muted
            onMouseEnter={(event) => event.target.play()}
            onMouseLeave={(event) => event.target.pause()}
            className=" object-fill h-[500px] w-full rounded-sm"
          ></video>
        </div>
        <div className=" flex gap-x-2 justify-start text-xs text-slate-800 mt-1.5">
          <span>33x Disukai</span>
          <span>100x Review</span>
          <span className=" cursor-pointer text-blue-600">Bagikan</span>
        </div>
      </div>
    </div>
  );
}

export default IndexCard;
