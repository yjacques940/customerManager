using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public class TimeSlotService : BaseCrudService<TimeSlot>
    {
        public TimeSlotService(IWebApiContext context) : base(context)
        {
        }
    }
}
