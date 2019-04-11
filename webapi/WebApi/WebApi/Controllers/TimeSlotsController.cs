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

        [HttpPost, Route("Add")]
        public ActionResult AddNewTimeSlot([FromBody]TimeSlot timeSlot)
        {
            if (Service.IsAvailable(timeSlot))
            {
                return Conflict();
            }
            else if (!Service.CreateNewTimeSlot(timeSlot))
            {
                return Conflict();
            }
            return Ok();
        }
    }
}
