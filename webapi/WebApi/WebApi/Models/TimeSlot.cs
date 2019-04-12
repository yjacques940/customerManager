using System;

namespace WebApi.Models
{
    public class TimeSlot : BaseModel
    {
        public DateTime StartDateTime { get; set; }
        public DateTime EndDateTime { get; set; }
        public DateTime DurationTime { get; set; }
        public bool IsPublic { get; set; }
        public bool IsAvailable { get; set; }
    }
}