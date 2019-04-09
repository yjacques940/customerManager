using System;

namespace WebApi.Models
{
    public class TimeSlot : BaseModel
    {
        public DateTime SlotDateTime { get; set; }
        public DateTime DurationTime { get; set; }
        public bool IsPublic { get; set; }
    }
}