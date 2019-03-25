using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace WebApi.Models
{
    public class Appointment : BaseModel
    {
        public DateTime Date { get; set; }
        public DateTime Time { get; set; }
        public TimeSpan DurationTime { get; set; }
    }
}
