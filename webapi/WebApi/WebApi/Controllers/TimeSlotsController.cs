using Microsoft.AspNetCore.Mvc;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class TimeSlotsController : BaseController<TimeSlotService, TimeSlot>
    {
        public TimeSlotsController(TimeSlotService service) : base(service)
        {
        }
    }
}
