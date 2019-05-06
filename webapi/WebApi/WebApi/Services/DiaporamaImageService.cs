using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Data;
using WebApi.DTO;
using WebApi.Models;

namespace WebApi.Services
{
    public class DiaporamaImageService : BaseCrudService<DiaporamaImage>
    {
        public DiaporamaImageService(IWebApiContext context) : base(context)
        {
        }

        internal bool AddNewImage(DiaporamaImage diaporamaImage)
        {
            diaporamaImage.IsActive = true;
            diaporamaImage.IsDisplayed = false;
            Context.Add(diaporamaImage);
            Context.SaveChanges();
            return true;
        }

        internal List<DiaporamaImage> GetAllDisplayedImages()
        {
            List<DiaporamaImage> diaporamaImages = Context.DiaporamaImages.Where(c => c.IsActive == true &&
                c.IsDisplayed == true).OrderBy(c => c.DisplayOrder).ToList();
            return diaporamaImages;
        }

        internal List<DiaporamaImage> GetAllImages()
        {
            return Context.DiaporamaImages.Where(c => c.IsActive == true).ToList(); ;
        }

        internal bool UpdateDisplayAndOrder(List<ImageDisplayAndOrderInformation> imageDisplayAndOrderInformation)
        { 
            foreach (var DisplayAndOrderInformation in imageDisplayAndOrderInformation)
            {
                var image = Context.DiaporamaImages.First(c => c.Id == DisplayAndOrderInformation.Id && c.IsActive);
                image.IsDisplayed = DisplayAndOrderInformation.IsDisplayed;
                image.DisplayOrder = DisplayAndOrderInformation.DisplayOrder;
                Context.SaveChanges();
            }
            return true;
        }
    }
}
