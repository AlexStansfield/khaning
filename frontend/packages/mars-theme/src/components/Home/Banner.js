// import { useEffect } from "react";
import { connect } from "frontity";
import imgFlowerF100 from "./../../assets/images/flowers/f100.png";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCheck } from "@fortawesome/free-solid-svg-icons";
import Carousel from "./Carousel";
import Link from "../link";

const Banner = ({ bannerButton, bannerContent, bannerLists, bannerSubtitle, bannerTitle, bannerEditor, carousels }) => {
  return (
    <>
      <div className="bg-texture-image pt-px-lg-120 pb-px-lg-120 pt-px-md-80 pb-px-md-70 pt-px-30 pb-px-60 overflow-hidden">
        <img
          className="ms-px-n-lg-45 d-lg-block d-none position-absolute top-px-lg-23 start-px-lg-0"
          src={imgFlowerF100}
          alt="hero flower image"
        />
        <div className="container">
          <div className="row gx-lg-5">
            <div className="col-lg-5 order-lg-1 mb-lg-0 mb-px-md-40 mb-px-30">
              <div className="position-relative">
                <Carousel
                  count={carousels.length}
                  carousels={carousels}
                />
              </div>
            </div>
            <div className="col-lg-7 my-auto pe-px-lg-20">
              <div className="me-px-lg-100 me-px-md-60">
                <h5
                  className="font-letter-space mb-px-8"
                  dangerouslySetInnerHTML={{ __html: bannerSubtitle }}
                />
                <h1
                  className="hero-font mb-px-28"
                  dangerouslySetInnerHTML={{ __html: bannerTitle }}
                />

                {bannerContent.value === "lists" ? (
                  <ul className="list-unstyled">
                    {bannerLists.map((list) => {
                      return (
                        <li key={list.text} className="mb-px-12">
                          <FontAwesomeIcon
                            icon={faCheck}
                            className="text-parsley me-px-9"
                          />
                          {list.text}
                        </li>
                      );
                    })}
                  </ul>
                ) : (
                  <div
                    dangerouslySetInnerHTML={{ __html: bannerEditor }}
                  ></div>
                )}
                {bannerButton && (
                  <Link
                    className="btn btn-parsley"
                    link={bannerButton.url}
                  >
                    {bannerButton.title}
                  </Link>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};
export default connect(Banner);
