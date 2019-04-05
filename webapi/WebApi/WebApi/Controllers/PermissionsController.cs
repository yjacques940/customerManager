using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    public class PermissionsController : BaseReaderController<PermissionService, Permission>
    {
        public PermissionsController(PermissionService service) : base(service)
        {
        }
    }
}
